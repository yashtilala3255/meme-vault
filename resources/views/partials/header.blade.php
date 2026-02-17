<!-- resources/views/partials/header.blade.php -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">
                🎭 MemeHub
            </a>
            
            <!-- Search Bar (Desktop) -->
            <div class="hidden md:block flex-1 max-w-xl mx-8">
                <form action="{{ route('templates.index') }}" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search meme templates..." 
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                </form>
            </div>
            
            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('templates.index') }}" class="text-gray-700 hover:text-primary transition">
                    Browse
                </a>
                <a href="{{ route('templates.random') }}" class="text-gray-700 hover:text-primary transition">
                    Random
                </a>
                <a href="{{ route('templates.index') }}" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
                    Create Meme
                </a>
            </div>
        </div>
        
        <!-- Mobile Search -->
        <div class="md:hidden mt-4">
            <form action="{{ route('templates.index') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search templates..." 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300"
                >
            </form>
        </div>
    </nav>
</header>