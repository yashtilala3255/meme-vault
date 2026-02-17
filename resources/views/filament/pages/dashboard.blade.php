<x-filament-panels::page>
    <!-- Welcome Card -->
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-red-500 to-pink-500 p-8 text-white shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black mb-2">Welcome Back, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-white/90 text-lg">Manage your meme empire from here</p>
            </div>
            <div class="hidden lg:block">
                <svg class="w-32 h-32 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Widgets -->
    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="4"
    />

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('filament.admin.resources.templates.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-red-100 opacity-50"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-2xl">📸</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Manage Templates</h3>
                <p class="text-gray-600 text-sm">Add, edit, and organize meme templates</p>
            </div>
        </a>

        <a href="{{ route('filament.admin.resources.categories.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-purple-100 opacity-50"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <span class="text-2xl">📁</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Categories</h3>
                <p class="text-gray-600 text-sm">Organize memes by categories</p>
            </div>
        </a>

        <a href="{{ route('filament.admin.resources.admins.index') }}" class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-blue-100 opacity-50"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl">👥</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Admins</h3>
                <p class="text-gray-600 text-sm">Manage admin users and permissions</p>
            </div>
        </a>
    </div>

    <!-- Data Widgets -->
    <x-filament-widgets::widgets
        :widgets="$this->getWidgets()"
        :columns="2"
    />
</x-filament-panels::page>