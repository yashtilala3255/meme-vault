<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ Safe download stats - no null errors
        $totalDownloads = $user->downloads()->count();
        $downloadsThisMonth = $user->downloads()
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        // ✅ Safe favorite category - handles empty downloads
        $favoriteCategory = 'N/A';

        if ($totalDownloads > 0) {
            $downloads = $user->downloads()
                ->with('template.category')
                ->get();

            $categoryNames = $downloads
                ->filter(fn($d) => $d->template && $d->template->category)
                ->pluck('template.category.name');

            if ($categoryNames->isNotEmpty()) {
                // ✅ Safe mode() - count manually instead
                $favoriteCategory = $categoryNames
                    ->countBy()
                    ->sortDesc()
                    ->keys()
                    ->first() ?? 'N/A';
            }
        }

        $stats = [
            'total_downloads'      => $totalDownloads,
            'downloads_this_month' => $downloadsThisMonth,
            'favorite_category'    => $favoriteCategory,
        ];

        // ✅ Safe recent downloads - handles empty state
        $recentDownloads = $user->downloads()
            ->with('template.category')
            ->latest()
            ->take(10)
            ->get()
            ->filter(fn($d) => $d->template !== null); // Remove orphaned downloads

        // ✅ Safe subscription - handles null
        $subscription = $user->activeSubscription ?? null;

        return view('dashboard', compact('user', 'stats', 'recentDownloads', 'subscription'));
    }
}