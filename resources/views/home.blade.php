@extends('layouts.app')

@section('title', 'MemeVault - Create Viral Memes in Seconds')

@section('content')

<!-- Hero Section with Animated Gradient -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden animated-gradient">
    <!-- Animated shapes background -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl animate-float"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl animate-float animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl animate-float animation-delay-4000"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-5xl mx-auto text-center">
            <!-- Main Headline -->
            <div class="mb-8 animate-slide-down">
                <span class="inline-block px-6 py-2 rounded-full bg-white/20 backdrop-blur-lg text-white font-semibold text-sm mb-6">
                    🎉 100% Free • No Login Required • Unlimited Downloads
                </span>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 leading-tight">
                    Create <span class="relative">
                        <span class="relative z-10">Viral</span>
                        <span class="absolute bottom-2 left-0 w-full h-4 bg-yellow-400 -rotate-1"></span>
                    </span> Memes
                    <br/>
                    in Seconds 
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto font-medium">
                    Access 100+ trending meme templates. Edit with powerful tools. Download instantly. Zero hassle. 100% awesome.
                </p>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 animate-slide-up">
                <a href="{{ route('templates.index') }}" class="group relative px-8 py-5 bg-white rounded-2xl font-bold text-lg text-gray-900 shadow-2xl hover:shadow-neon-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Start Creating Now
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                <a href="{{ route('templates.random') }}" class="px-8 py-5 bg-white/10 backdrop-blur-lg rounded-2xl font-bold text-lg text-white border-2 border-white/30 hover:bg-white/20 transition-all duration-300 flex items-center gap-3">
                    <span class="text-2xl animate-wiggle"></span>
                    Random Meme
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto">
                <div class="glass rounded-2xl p-6 backdrop-blur-lg">
                    <div class="text-4xl font-black text-white mb-2">{{ number_format($stats['total_templates']) }}+</div>
                    <div class="text-white/80 font-semibold">Templates</div>
                </div>
                <div class="glass rounded-2xl p-6 backdrop-blur-lg">
                    <div class="text-4xl font-black text-white mb-2">{{ number_format($stats['total_downloads']) }}+</div>
                    <div class="text-white/80 font-semibold">Downloads</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>

<!-- Trending Templates Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-16 animate-slide-up">
            <span class="inline-block px-6 py-2 rounded-full bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-bold text-sm mb-4">
                🔥 HOT RIGHT NOW
            </span>
            <h2 class="text-4xl md:text-6xl font-black mb-4 bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                Trending Templates
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                The most popular memes that are breaking the internet right now
            </p>
        </div>
        
        <!-- Templates Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
            @foreach($featuredTemplates as $template)
                <div class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scale-in">
                    <!-- Image -->
                    <div class="aspect-square overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                        <img 
                            src="{{ $template->image_url }}" 
                            alt="{{ $template->name }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                            loading="lazy"
                        >
                    </div>
                    
                    <!-- Overlay with gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-6">
                        <div class="transform translate-y-8 group-hover:translate-y-0 transition-transform duration-300 w-full space-y-3">
                            <a href="{{ route('editor.edit', $template) }}" class="block w-full bg-white text-gray-900 text-center py-3 rounded-xl font-bold hover:bg-gray-100 transition-colors">
                                Edit Now
                            </a>
                            <a href="{{ route('templates.download', $template) }}" class="block w-full bg-primary-500 text-white text-center py-3 rounded-xl font-bold hover:bg-primary-600 transition-colors">
                                Download
                            </a>
                        </div>
                    </div>
                    
                    <!-- Info bar -->
                    <div class="p-4 bg-white">
                        <h3 class="font-bold text-gray-900 truncate mb-2">{{ $template->name }}</h3>
                        <div class="flex items-center justify-between text-sm">
                            <span class="px-3 py-1 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 font-semibold text-xs">
                                {{ $template->category->name }}
                            </span>
                            <span class="text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                                </svg>
                                {{ number_format($template->download_count) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Trending badge -->
                    @if($loop->index < 3)
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg animate-pulse">
                            🔥 #{{ $loop->index + 1 }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- View All Button -->
        <div class="text-center">
            <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-2xl font-bold text-lg shadow-lg hover:shadow-neon transition-all duration-300 transform hover:scale-105">
                View All {{ number_format($stats['total_templates']) }} Templates
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-6 py-2 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold text-sm mb-4">
                📂 BROWSE BY CATEGORY
            </span>
            <h2 class="text-4xl md:text-6xl font-black mb-4 text-gray-900">
                Find Your Vibe
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Explore memes organized by category and mood
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-100 to-gray-200 p-8 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-secondary-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="text-6xl mb-4 transform group-hover:scale-110 transition-transform duration-300">
                            {{ $category->icon ?? '📁' }}
                        </div>
                        <div class="font-bold text-gray-900 mb-2">{{ $category->name }}</div>
                        <div class="text-sm text-gray-500">{{ $category->templates_count }} memes</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Features/How It Works Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-6 py-2 rounded-full bg-gradient-to-r from-green-500 to-blue-500 text-white font-bold text-sm mb-4">
                ⚡ SUPER SIMPLE
            </span>
            <h2 class="text-4xl md:text-6xl font-black mb-4 text-gray-900">
                How It Works
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-3xl blur opacity-25 group-hover:opacity-75 transition duration-500"></div>
                <div class="relative bg-white rounded-3xl p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 text-white text-3xl font-black mb-6">
                        1
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Choose Template</h3>
                    <p class="text-gray-600">
                        Browse our collection of 100+ viral meme templates
                    </p>
                </div>
            </div>
            
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-secondary-500 to-purple-500 rounded-3xl blur opacity-25 group-hover:opacity-75 transition duration-500"></div>
                <div class="relative bg-white rounded-3xl p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-secondary-500 to-secondary-600 text-white text-3xl font-black mb-6">
                        2
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Customize</h3>
                    <p class="text-gray-600">
                        Add your text, adjust fonts, colors, and make it yours
                    </p>
                </div>
            </div>
            
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-3xl blur opacity-25 group-hover:opacity-75 transition duration-500"></div>
                <div class="relative bg-white rounded-3xl p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white text-3xl font-black mb-6">
                        3
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Download</h3>
                    <p class="text-gray-600">
                        Download instantly in high quality. No watermarks!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 via-purple-600 to-secondary-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl animate-float"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl animate-float animation-delay-2000"></div>
    </div>
    
    <div class="container mx-auto px-4 text-center relative z-10">
        <h2 class="text-4xl md:text-6xl font-black text-white mb-6">
            Ready to Create?
        </h2>
        <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl mx-auto">
            Join thousands of meme creators. Start making viral content today!
        </p>
        <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-gray-900 rounded-2xl font-black text-xl shadow-2xl hover:shadow-neon-lg transform hover:scale-105 transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Start Creating Free
        </a>
    </div>
</section>

@endsection