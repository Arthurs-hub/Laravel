@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('system.status_title') }}</h1>
                <p class="text-gray-600">{{ __('system.status_description') }}</p>
            </div>

            <!-- System Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                <!-- System Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 ml-3">{{ __('system.system_info') }}</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.php_version') }}:</span>
                            <span class="font-medium">{{ $systemInfo['php_version'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.laravel_version') }}:</span>
                            <span class="font-medium">{{ $systemInfo['laravel_version'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.environment') }}:</span>
                            <span class="font-medium capitalize">{{ $systemInfo['environment'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.timezone') }}:</span>
                            <span class="font-medium">{{ $systemInfo['timezone'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.uptime') }}:</span>
                            <span class="font-medium">{{ $systemInfo['uptime'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Database Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 ml-3">{{ __('system.database_info') }}</h3>
                    </div>
                    @if($databaseInfo['status'] === 'connected')
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.status') }}:</span>
                                <span class="text-green-600 font-medium">{{ __('system.connected') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.database_size') }}:</span>
                                <span class="font-medium">{{ $databaseInfo['database_size_mb'] }} MB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.total_users') }}:</span>
                                <span class="font-medium">{{ number_format($databaseInfo['total_users']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.total_hotels') }}:</span>
                                <span class="font-medium">{{ number_format($databaseInfo['total_hotels']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.total_rooms') }}:</span>
                                <span class="font-medium">{{ number_format($databaseInfo['total_rooms']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.total_bookings') }}:</span>
                                <span class="font-medium">{{ number_format($databaseInfo['total_bookings']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.total_reviews') }}:</span>
                                <span class="font-medium">{{ number_format($databaseInfo['total_reviews']) }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-red-600">
                            <p class="font-medium">{{ __('system.database_error') }}</p>
                            <p class="text-sm mt-1">{{ $databaseInfo['error'] ?? '' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Performance Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 ml-3">{{ __('system.performance_info') }}</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.memory_usage') }}:</span>
                            <span class="font-medium">{{ $performanceInfo['memory_usage_mb'] }} MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.memory_limit') }}:</span>
                            <span class="font-medium">{{ $performanceInfo['memory_limit_mb'] }} MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.memory_usage_percent') }}:</span>
                            <span class="font-medium">{{ $performanceInfo['memory_usage_percent'] }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.cache_status') }}:</span>
                            <span
                                class="font-medium {{ $performanceInfo['cache_status'] === 'working' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $performanceInfo['cache_status'] === 'working' ? __('system.working') : __('system.error') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.queue_status') }}:</span>
                            <span
                                class="font-medium {{ str_contains($performanceInfo['queue_status'], 'healthy') ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $performanceInfo['queue_status'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Storage Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 ml-3">{{ __('system.storage_info') }}</h3>
                    </div>
                    @if(!isset($storageInfo['status']) || $storageInfo['status'] !== 'error')
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.total_space') }}:</span>
                                <span class="font-medium">{{ $storageInfo['total_space_gb'] }} GB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.used_space') }}:</span>
                                <span class="font-medium">{{ $storageInfo['used_space_gb'] }} GB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.free_space') }}:</span>
                                <span class="font-medium">{{ $storageInfo['free_space_gb'] }} GB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.usage_percent') }}:</span>
                                <span class="font-medium">{{ $storageInfo['usage_percent'] }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('system.storage_status') }}:</span>
                                <span
                                    class="font-medium {{ $storageInfo['storage_status'] === 'writable' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $storageInfo['storage_status'] === 'writable' ? __('system.writable') : __('system.error') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-red-600">
                            <p class="font-medium">{{ __('system.storage_error') }}</p>
                            <p class="text-sm mt-1">{{ $storageInfo['error'] ?? '' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 ml-3">{{ __('system.recent_activity') }}</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.recent_bookings') }}:</span>
                            <span class="font-medium">{{ number_format($databaseInfo['recent_bookings'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.pending_reviews') }}:</span>
                            <span class="font-medium">{{ number_format($databaseInfo['pending_reviews'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('system.total_facilities') }}:</span>
                            <span class="font-medium">{{ number_format($databaseInfo['total_facilities'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div class="text-center">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('system.back_to_dashboard') }}
                </a>
            </div>
        </div>
    </div>
@endsection