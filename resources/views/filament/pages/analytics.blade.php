<x-filament-panels::page>
    @php
        $data = $this->getViewData();
    @endphp

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard Overview</h1>
        <p class="text-sm text-gray-500">Welcome back! Here's what's happening with your templates today.</p>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Templates -->
        <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <x-heroicon-o-photo class="w-6 h-6 text-blue-600" />
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Templates</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($data['totalTemplates']) }}</dd>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">{{ $data['activeTemplates'] }} active</span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-gray-500">{{ $data['featuredTemplates'] }} featured</span>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-24 h-24 -mb-6 -mr-6 opacity-10">
                <x-heroicon-o-photo class="w-full h-full text-blue-600" />
            </div>
        </div>

        <!-- Downloads -->
        <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-green-50 rounded-lg">
                            <x-heroicon-o-arrow-down-tray class="w-6 h-6 text-green-600" />
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Downloads</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($data['totalDownloads']) }}</dd>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500">Avg. {{ number_format($data['avgDownloadsPerTemplate'], 1) }} per template</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Views -->
        <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <x-heroicon-o-eye class="w-6 h-6 text-purple-600" />
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Views</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ number_format($data['totalViews']) }}</dd>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500">Avg. {{ number_format($data['avgViewsPerTemplate'], 1) }} per template</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Engagement -->
        <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-orange-50 rounded-lg">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-orange-600" />
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Engagement Rate</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ $data['engagementRate'] }}%</dd>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $data['engagementRate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Downloaded -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Top Downloaded Templates</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Top 5
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($data['topDownloaded'] as $index => $template)
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-500 w-6">{{ $index + 1 }}.</span>
                            <img src="{{ $template->image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $template->name }}</p>
                                <p class="text-xs text-gray-500">{{ $template->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($template->download_count) }}</p>
                                <p class="text-xs text-gray-500">downloads</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Viewed -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Top Viewed Templates</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Top 5
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($data['topViewed'] as $index => $template)
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-500 w-6">{{ $index + 1 }}.</span>
                            <img src="{{ $template->image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $template->name }}</p>
                                <p class="text-xs text-gray-500">{{ $template->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($template->view_count) }}</p>
                                <p class="text-xs text-gray-500">views</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Category Distribution</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($data['categoryStats'] as $category)
                    <div class="relative p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-500 transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $category->icon }}</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                <p class="text-xs text-gray-500">{{ $category->templates_count }} templates</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Templates -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Recently Added</h3>
                <a href="{{ route('filament.admin.resources.templates.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                    View all →
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($data['recentTemplates'] as $template)
                    <a href="{{ route('filament.admin.resources.templates.edit', $template) }}" class="group block">
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 mb-2">
                            <img src="{{ $template->image_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </div>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $template->name }}</p>
                        <p class="text-xs text-gray-500">{{ $template->created_at->diffForHumans() }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>