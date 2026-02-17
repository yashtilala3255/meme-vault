<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class EditorController extends Controller
{
    public function edit(Template $template)
    {
        // ✅ Increment view count
        $template->increment('view_count');

        // ✅ Get a local/proxied image URL safe for canvas
        $localImageUrl = $this->getLocalImageUrl($template);

        return view('editor.edit', compact('template', 'localImageUrl'));
    }

    private function getLocalImageUrl(Template $template): string
    {
        // If image is already stored locally, use it
        if ($template->local_image_path && Storage::disk('public')->exists($template->local_image_path)) {
            return Storage::url($template->local_image_path);
        }

        // Try to download and cache it locally
        try {
            $imageUrl = $template->image_url;
            
            if (empty($imageUrl)) {
                return asset('images/placeholder.jpg');
            }

            $response = Http::timeout(10)->get($imageUrl);
            
            if ($response->successful()) {
                $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'templates/' . $template->slug . '.' . $extension;
                
                Storage::disk('public')->put($filename, $response->body());
                
                // Save local path to template
                $template->update(['local_image_path' => $filename]);
                
                return Storage::url($filename);
            }
        } catch (\Exception $e) {
            // Fall through to original URL
        }

        // Return original URL as fallback
        return $template->image_url;
    }

    public function proxyImage(Request $request)
    {
        $url = $request->get('url');
        
        if (empty($url)) {
            abort(400, 'No URL provided');
        }

        try {
            $response = Http::timeout(15)->get($url);
            
            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'image/jpeg';
                
                return response($response->body(), 200)
                    ->header('Content-Type', $contentType)
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Cache-Control', 'public, max-age=86400');
            }
        } catch (\Exception $e) {
            abort(500, 'Failed to fetch image');
        }

        abort(404);
    }
}