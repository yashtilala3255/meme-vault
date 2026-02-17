@extends('layouts.app')

@section('title', $template->name . ' - MemeVault')

@section('content')

<!-- Breadcrumb -->
<section class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600 transition-colors">Home</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-primary-600 transition-colors">Templates</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-semibold">{{ $template->name }}</span>
        </nav>
    </div>
</section>

<!-- Template Details -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-7xl mx-auto">
            
            <!-- Image Column -->
            <div class="space-y-6">
                <div class="relative group rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-gray-100 to-gray-200">
                    <img 
                        src="{{ $template->image_url }}" 
                        alt="{{ $template->name }}"
                        class="w-full h-auto transform group-hover:scale-105 transition-transform duration-500"
                    >
                    
                    <!-- Zoom icon -->
                    <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-sm text-white p-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('editor.edit', $template) }}" class="group relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center py-5 rounded-2xl font-bold text-lg shadow-lg hover:shadow-neon transition-all transform hover:scale-105">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Meme
                        </span>
                    </a>
                    <a href="{{ route('templates.download', $template) }}" class="bg-white border-2 border-gray-200 text-gray-900 text-center py-5 rounded-2xl font-bold text-lg hover:border-primary-500 hover:text-primary-600 transition-all flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
            
            <!-- Info Column -->
            <div class="space-y-8">
                <!-- Title & Category -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-4 py-2 rounded-xl bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 font-bold text-sm">
                            {{ $template->category->icon }} {{ $template->category->name }}
                        </span>
                        @if($template->is_featured)
                            <span class="px-4 py-2 rounded-xl bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Featured
                            </span>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                        {{ $template->name }}
                    </h1>
                    <p class="text-lg text-gray-600">
                        Create your own version of this viral meme template
                    </p>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6 border border-primary-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-primary-500 text-white p-2 rounded-xl">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-3xl font-black text-primary-700">{{ number_format($template->download_count) }}</div>
                                <div class="text-sm text-primary-600 font-semibold">Downloads</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-2xl p-6 border border-secondary-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-secondary-500 text-white p-2 rounded-xl">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-3xl font-black text-secondary-700">{{ number_format($template->view_count) }}</div>
                                <div class="text-sm text-secondary-600 font-semibold">Views</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tags -->
                @if($template->tags->count() > 0)
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Tags
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($template->tags as $tag)
                                <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition-colors cursor-pointer">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Template Details -->
                <div class="bg-gray-50 rounded-2xl p-6 space-y-3">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Template Details</h3>
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Dimensions</span>
                        <span class="font-bold text-gray-900">{{ $template->width }} × {{ $template->height }}px</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Added</span>
                        <span class="font-bold text-gray-900">{{ $template->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-600">Category</span>
                        <a href="{{ route('categories.show', $template->category) }}" class="font-bold text-primary-600 hover:underline">
                            {{ $template->category->name }}
                        </a>
                    </div>
                </div>
                
                <!-- Share -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share this template
                    </h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('templates.show', $template)) }}" target="_blank" class="flex-1 bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('templates.show', $template)) }}&text={{ urlencode($template->name) }}" target="_blank" class="flex-1 bg-sky-500 text-white text-center py-3 rounded-xl font-semibold hover:bg-sky-600 transition-colors">
                            Twitter
                        </a>
                        <a href="https://reddit.com/submit?url={{ urlencode(route('templates.show', $template)) }}&title={{ urlencode($template->name) }}" target="_blank" class="flex-1 bg-orange-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-orange-700 transition-colors">
                            Reddit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Templates -->
@if($relatedTemplates->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-black text-gray-900">Similar Templates</h2>
                <a href="{{ route('categories.show', $template->category) }}" class="text-primary-600 font-bold hover:underline flex items-center gap-2">
                    View all in {{ $template->category->name }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($relatedTemplates as $related)
                    <a href="{{ route('templates.show', $related) }}" class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="aspect-square overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                            <img 
                                src="{{ $related->image_url }}" 
                                alt="{{ $related->name }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $related->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection