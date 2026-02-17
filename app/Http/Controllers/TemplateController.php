<?php

// app/Http/Controllers/TemplateController.php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::with(['category', 'tags'])
            ->where('is_active', true);
        
        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }
        
        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Premium filter - only show accessible templates
        $user = Auth::user();
        if (!$user || !$user->isPremium()) {
            // Free users can only see free templates
            $query->where('is_premium', false);
        } elseif ($user->isPremium() && !$user->isBusiness()) {
            // Premium users can see free and premium (but not business-only)
            $query->where(function ($q) {
                $q->where('is_premium', false)
                ->orWhere('premium_tier', 'premium');
            });
        }
        // Business users can see everything (no filter needed)
        
        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'trending':
                $query->where('created_at', '>=', now()->subDays(7))
                    ->orderBy('view_count', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }
        
        $templates = $query->paginate(24);
        $categories = Category::withCount('templates')->get();
        
        return view('templates.index', compact('templates', 'categories'));
    }
    
    public function show(Template $template)
    {
        $template->incrementViews();
        $template->load(['category', 'tags']);
        
        $relatedTemplates = Template::where('category_id', $template->category_id)
            ->where('id', '!=', $template->id)
            ->where('is_active', true)
            ->take(6)
            ->get();
        
        return view('templates.show', compact('template', 'relatedTemplates'));
    }
    
    public function download(Template $template)
{
    // ✅ Increment download count
    $template->increment('download_count');

    // ✅ Track download for logged-in users
    if (auth()->check()) {
        try {
            \App\Models\UserDownload::create([
                'user_id'           => auth()->id(),
                'template_id'       => $template->id,
                'watermark_removed' => auth()->user()->canRemoveWatermark(),
                'ip_address'        => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Don't block download if tracking fails
        }
    }

    // ✅ Build download filename
    $extension    = strtolower(pathinfo($template->image_path, PATHINFO_EXTENSION)) ?: 'jpg';
    $downloadName = $template->slug . '.' . $extension;

    // ✅ STRATEGY 1: Use image_path directly (already stored locally)
    if ($template->image_path) {
        $fullPath = storage_path('app/public/' . $template->image_path);

        if (file_exists($fullPath)) {
            return response()->download($fullPath, $downloadName, [
                'Content-Type'        => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            ]);
        }
    }

    // ✅ STRATEGY 2: Fetch from image_url and serve
    try {
        $imageUrl  = $template->image_url;
        $imageData = file_get_contents($imageUrl, false, stream_context_create([
            'http' => [
                'timeout'    => 20,
                'user_agent' => 'Mozilla/5.0',
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]));

        if ($imageData !== false) {
            return response($imageData, 200, [
                'Content-Type'        => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
                'Content-Length'      => strlen($imageData),
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            ]);
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Download failed for ' . $template->slug . ': ' . $e->getMessage());
    }

    return back()->with('error', 'Download failed. Please try again.');
}
    public function random()
    {
        $template = Template::where('is_active', true)->inRandomOrder()->first();
        
        if ($template) {
            return redirect()->route('editor.edit', $template);
        }
        
        return redirect()->route('templates.index');
    }

    private function downloadWithWatermark(Template $template)
    {
        $imagePath = storage_path('app/public/' . $template->image_path);
        
        // Create image from file
        $image = imagecreatefromjpeg($imagePath);
        
        // Add watermark text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $watermarkText = 'MemeVault.com';
        
        // Get image dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Add watermark at bottom right
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($watermarkText);
        $x = $width - $textWidth - 10;
        $y = $height - 20;
        
        imagestring($image, $fontSize, $x, $y, $watermarkText, $textColor);
        
        // Output to temp file
        $tempPath = storage_path('app/temp/' . $template->slug . '-watermarked.jpg');
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        imagejpeg($image, $tempPath, 90);
        imagedestroy($image);
        
        return response()->download($tempPath, $template->slug . '.jpg')->deleteFileAfterSend();
    }
}