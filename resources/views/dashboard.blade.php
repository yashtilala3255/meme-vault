@extends('layouts.app')

@section('title', 'Dashboard - MemeVault')

@section('content')

<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="container mx-auto px-4">

        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">
                Welcome back, {{ $user->name ?? 'User' }}! 👋
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Here's what's happening with your account
            </p>
        </div>

        <!-- Subscription Status Banner -->
        @if($user->isPremium())
            <div class="mb-8 p-6 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-2xl text-white shadow-lg">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/20 backdrop-blur-lg rounded-xl">
                            @if($user->isBusiness())
                                <span class="text-3xl">🚀</span>
                            @else
                                <span class="text-3xl">💎</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-black mb-1">
                                {{ $user->isBusiness() ? 'Business' : 'Premium' }} Member
                            </h3>
                            <p class="text-white/80">
                                @if($subscription)
                                    Active until {{ $subscription->expires_at?->format('M d, Y') ?? 'N/A' }}
                                @else
                                    Active subscription
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('subscription.show') }}"
                       class="px-6 py-3 bg-white text-primary-600 rounded-xl font-bold hover:bg-gray-100 transition-colors whitespace-nowrap">
                        Manage Subscription
                    </a>
                </div>
            </div>
        @else
            <div class="mb-8 p-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl text-white shadow-lg">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/20 backdrop-blur-lg rounded-xl">
                            <span class="text-3xl">⭐</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black mb-1">Upgrade to Premium</h3>
                            <p class="text-white/90">Remove watermarks, access premium templates & more!</p>
                        </div>
                    </div>
                    <a href="{{ route('pricing') }}"
                       class="px-6 py-3 bg-white text-orange-600 rounded-xl font-bold hover:bg-gray-100 transition-colors whitespace-nowrap">
                        View Plans
                    </a>
                </div>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Total Downloads -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-xl flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">
                            {{ number_format($stats['total_downloads']) }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Downloads</p>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-xl flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">
                            {{ number_format($stats['downloads_this_month']) }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">This Month</p>
                    </div>
                </div>
            </div>

            <!-- Favorite Category -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-xl flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xl font-black text-gray-900 dark:text-white truncate">
                            {{ $stats['favorite_category'] }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Favorite Category</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Downloads -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Downloads</h3>
                <a href="{{ route('templates.index') }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                    Browse more →
                </a>
            </div>

            @if($recentDownloads->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        @foreach($recentDownloads as $download)
                            {{-- ✅ Extra null safety in view --}}
                            @if($download->template)
                                <a href="{{ route('templates.show', $download->template) }}" class="group block">
                                    <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 mb-2 shadow-md group-hover:shadow-xl transition-shadow">
                                        <img
                                            src="{{ $download->template->image_url ?? '/images/placeholder.jpg' }}"
                                            alt="{{ $download->template->name ?? 'Template' }}"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.src='/images/placeholder.jpg'"
                                        >
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $download->template->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $download->created_at->diffForHumans() }}
                                    </p>
                                    @if($download->watermark_removed)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full text-xs font-bold">
                                            ✓ No Watermark
                                        </span>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No downloads yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Start creating memes to see your downloads here!
                    </p>
                    <a href="{{ route('templates.index') }}"
                       class="inline-block px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-bold hover:from-primary-600 hover:to-primary-700 transition-colors shadow-lg">
                        Browse Templates
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Links -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('templates.index') }}"
               class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-lg border-2 border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 transition-colors text-center group">
                <div class="text-3xl mb-2">🎨</div>
                <p class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Browse Templates</p>
            </a>
            <a href="{{ route('pricing') }}"
               class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-lg border-2 border-gray-200 dark:border-gray-700 hover:border-yellow-500 dark:hover:border-yellow-500 transition-colors text-center group">
                <div class="text-3xl mb-2">💎</div>
                <p class="font-bold text-gray-900 dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">Upgrade Plan</p>
            </a>
            <a href="{{ route('support') }}"
               class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-lg border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-colors text-center group">
                <div class="text-3xl mb-2">🎫</div>
                <p class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Support Tickets</p>
            </a>
            <a href="{{ route('templates.random') }}"
               class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-lg border-2 border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 transition-colors text-center group">
                <div class="text-3xl mb-2">🎲</div>
                <p class="font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Random Meme</p>
            </a>
        </div>

    </div>
</div>

@endsection