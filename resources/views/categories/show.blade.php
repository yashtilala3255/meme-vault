@extends('layouts.app')

@section('title', $category->name . ' Meme Templates - MemeVault')

@section('content')

<!-- Category Hero -->
<section class="relative py-20 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-purple-600 to-secondary-600">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl animate-float"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl animate-float animation-delay-2000"></div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Breadcrumb -->
            <nav class="flex items-center justify-center space-x-2 text-sm mb-6">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-white transition-colors">Home</a>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('templates.index') }}" class="text-white/70 hover:text-white transition-colors">Templates</a>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white font-semibold">{{ $category->name }}</span>
            </nav>
            
            <!-- Category Icon & Title -->
            <div class="mb-8 animate-scale-in">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-white/10 backdrop-blur-lg mb-6 transform hover:scale-110 transition-transform">
                    <span class="text-7xl">{{ $category->icon ?? '📁' }}</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-xl md:text-2xl text-white/90 mb-6 max-w-2xl mx-auto">
                        {{ $category->description }}
                    </p>
                @endif
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white/20 backdrop-blur-lg text-white font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $templates->total() }} Templates Available
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Templates Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        
        <!-- Sort & Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Browse {{ $category->name }}
                </h2>
                <p class="text-gray-600">
                    Found <span class="font-bold text-primary-600">{{ $templates->total() }}</span> templates
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <label class="text-sm font-semibold text-gray-700">Sort by:</label>
                <select onchange="window.location.href=this.value" class="px-4 py-2 bg-white border-2 border-gray-200 rounded-xl text-gray-700 font-semibold focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all">
                    <option value="{{ route('categories.show', $category) }}" {{ !request('sort') ? 'selected' : '' }}>Latest</option>
                    <option value="{{ route('categories.show', [$category, 'sort' => 'popular']) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="{{ route('categories.show', [$category, 'sort' => 'trending']) }}" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending</option>
                </select>
            </div>
        </div>
        
        @if($templates->count() > 0)
            <!-- Templates Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6 mb-12">
                @foreach($templates as $template)
                    <div class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in">
                        <!-- Image -->
                        <div class="aspect-square overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 relative">
                            <img 
                                src="{{ $template->image_url }}" 
                                alt="{{ $template->name }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                loading="lazy"
                            >
                            
                            <!-- Quick Actions Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <a href="{{ route('editor.edit', $template) }}" class="w-full bg-white text-gray-900 text-center py-3 px-4 rounded-xl font-bold hover:bg-gray-100 transition-colors flex items-center justify-center gap-2 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <div class="flex gap-2 w-full">
                                        <a href="{{ route('templates.show', $template) }}" class="flex-1 bg-gray-800 text-white text-center py-2 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('templates.download', $template) }}" class="flex-1 bg-primary-500 text-white text-center py-2 rounded-xl font-semibold hover:bg-primary-600 transition-colors">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Featured Badge -->
                            @if($template->is_featured)
                                <div class="absolute top-2 left-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-2 py-1 rounded-lg text-xs font-bold shadow-lg">
                                    ⭐ Featured
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="p-3">
                            <h3 class="font-bold text-sm text-gray-900 truncate mb-2">
                                {{ $template->name }}
                            </h3>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ number_format($template->download_count) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ number_format($template->view_count) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $templates->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                    <span class="text-6xl">{{ $category->icon ?? '📁' }}</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">No templates yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    We're working on adding more templates to this category. Check back soon!
                </p>
                <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-2xl font-bold hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Browse All Templates
                </a>
            </div>
        @endif
    </div>
</section>

@endsection