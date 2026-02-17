@extends('layouts.app')

@section('title', 'Browse Meme Templates - MemeVault')

@section('content')

<!-- Hero Header -->
<section class="bg-gradient-to-br from-primary-600 via-purple-600 to-secondary-600 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-black text-white mb-4">
                Explore Templates
            </h1>
            <p class="text-xl text-white/90 mb-8">
                {{ $templates->total() }} meme templates ready to customize
            </p>
            
            <!-- Enhanced Search -->
            <form action="{{ route('templates.index') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="relative group">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by name, keyword, or meme..." 
                        value="{{ request('search') }}"
                        class="w-full pl-14 pr-32 py-5 bg-white/10 backdrop-blur-lg border-2 border-white/30 rounded-2xl focus:outline-none focus:border-white focus:ring-4 focus:ring-white/20 transition-all duration-300 text-white placeholder-white/60 text-lg"
                    >
                    <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-white/60 group-focus-within:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white text-primary-600 px-6 py-3 rounded-xl font-bold hover:bg-gray-100 transition-colors">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Filters & Content -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters -->
            <aside class="lg:w-80 flex-shrink-0">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Quick Filters -->
                    <div class="bg-white rounded-3xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Quick Filters
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('templates.index') }}" class="block px-4 py-2 rounded-xl {{ !request('sort') ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                                Latest
                            </a>
                            <a href="{{ route('templates.index', ['sort' => 'popular']) }}" class="block px-4 py-2 rounded-xl {{ request('sort') == 'popular' ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                                Most Popular
                            </a>
                            <a href="{{ route('templates.index', ['sort' => 'trending']) }}" class="block px-4 py-2 rounded-xl {{ request('sort') == 'trending' ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                                Trending
                            </a>
                        </div>
                    </div>
                    
                    <!-- Categories Filter -->
                    <div class="bg-white rounded-3xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Categories
                        </h3>
                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            <a href="{{ route('templates.index') }}" class="flex items-center justify-between px-4 py-2 rounded-xl {{ !request('category') ? 'bg-gray-100 font-semibold' : 'hover:bg-gray-50' }} text-gray-700 transition-colors">
                                <span>All Categories</span>
                                <span class="text-xs bg-primary-100 text-primary-700 px-2 py-1 rounded-full">{{ $templates->total() }}</span>
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('templates.index', ['category' => $category->slug]) }}" class="flex items-center justify-between px-4 py-2 rounded-xl {{ request('category') == $category->slug ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold' : 'hover:bg-gray-50' }} text-gray-700 transition-colors">
                                    <span class="flex items-center gap-2">
                                        <span>{{ $category->icon }}</span>
                                        <span>{{ $category->name }}</span>
                                    </span>
                                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">{{ $category->templates_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Reset Filters -->
                    @if(request()->hasAny(['search', 'category', 'sort', 'tag']))
                        <a href="{{ route('templates.index') }}" class="block w-full px-6 py-3 bg-gray-200 text-gray-700 text-center rounded-2xl font-semibold hover:bg-gray-300 transition-colors">
                            Reset All Filters
                        </a>
                    @endif
                </div>
            </aside>
            
            <!-- Main Content -->
            <div class="flex-1">
                
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">
                            @if(request('search'))
                                Search: "{{ request('search') }}"
                            @elseif(request('category'))
                                {{ $templates->first()->category->name ?? 'Templates' }}
                            @else
                                All Templates
                            @endif
                        </h2>
                        <p class="text-gray-600">
                            Found <span class="font-bold text-primary-600">{{ $templates->total() }}</span> templates
                        </p>
                    </div>
                    
                    <!-- View Toggle & Sort (Mobile) -->
                    <div class="flex items-center gap-3">
                        <select onchange="window.location.href=this.value" class="px-4 py-2 bg-white border-2 border-gray-200 rounded-xl text-gray-700 font-semibold focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all">
                            <option value="{{ route('templates.index', array_merge(request()->except('sort'), [])) }}" {{ !request('sort') ? 'selected' : '' }}>Latest</option>
                            <option value="{{ route('templates.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="{{ route('templates.index', array_merge(request()->except('sort'), ['sort' => 'trending'])) }}" {{ request('sort') == 'trending' ? 'selected' : '' }}>Trending</option>
                            <option value="{{ route('templates.index', array_merge(request()->except('sort'), ['sort' => 'name'])) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>A-Z</option>
                        </select>
                    </div>
                </div>
                
                <!-- Templates Grid -->
                @if($templates->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
                        @foreach($templates as $template)
                            <div class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in">
                                <!-- Image Container -->
                                <div class="aspect-square overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 relative">
                                    <img 
                                        src="{{ $template->image_url }}" 
                                        alt="{{ $template->name }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                        loading="lazy"
                                    >
                                    
                                    <!-- Quick Action Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 p-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                            <a href="{{ route('editor.edit', $template) }}" class="w-full bg-white text-gray-900 text-center py-3 px-4 rounded-xl font-bold hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <div class="flex gap-2 w-full">
                                                <a href="{{ route('templates.show', $template) }}" class="flex-1 bg-gray-800 text-white text-center py-2 px-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('templates.download', $template) }}" class="flex-1 bg-primary-500 text-white text-center py-2 px-3 rounded-xl font-semibold hover:bg-primary-600 transition-colors">
                                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Featured Badge -->
                                    @if($template->is_featured)
                                        <div class="absolute top-3 left-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Featured
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Info -->
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 truncate mb-3 text-sm md:text-base">
                                        {{ $template->name }}
                                    </h3>
                                    <div class="flex items-center justify-between">
                                        <span class="px-3 py-1 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 font-semibold text-xs truncate max-w-[120px]">
                                            {{ $template->category->name }}
                                        </span>
                                        <div class="flex items-center gap-3 text-xs text-gray-500">
                                            <span class="flex items-center gap-1" title="Downloads">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ number_format($template->download_count) }}
                                            </span>
                                            <span class="flex items-center gap-1" title="Views">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ number_format($template->view_count) }}
                                            </span>
                                        </div>
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
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">No templates found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            We couldn't find any memes matching your search. Try different keywords or browse all templates.
                        </p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('templates.index') }}" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-2xl font-bold hover:shadow-lg transition-all">
                                View All Templates
                            </a>
                            <a href="{{ route('templates.random') }}" class="px-6 py-3 bg-gray-200 text-gray-900 rounded-2xl font-bold hover:bg-gray-300 transition-colors">
                                🎲 Random Meme
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection