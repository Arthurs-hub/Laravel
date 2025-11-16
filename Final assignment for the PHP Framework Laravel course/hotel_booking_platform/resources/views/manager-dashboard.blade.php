@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Заголовок - адаптивная версия -->
            <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-6 sm:p-8 mb-8">
                <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">🏨
                            {{ __('manager.manager_dashboard') }}</h1>
                        <p class="text-gray-600 text-sm sm:text-base">
                            {{ __('manager.hotel_management', ['hotel_title' => $hotel->title]) }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-sm text-gray-500">{{ $hotel->country }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $hotel->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">{{ __('manager.total_rooms') }}</p>
                            <p class="text-3xl font-bold text-black">{{ $stats['total_rooms'] }}</p>
                        </div>
                        <div class="text-4xl">🏠</div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">{{ __('manager.total_bookings') }}</p>
                            <p class="text-3xl font-bold text-black">{{ $stats['total_bookings'] }}</p>
                        </div>
                        <div class="text-4xl">📅</div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">{{ __('manager.active_bookings') }}</p>
                            <p class="text-3xl font-bold text-black">{{ $stats['active_bookings'] }}</p>
                        </div>
                        <div class="text-4xl">🔥</div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">{{ __('manager.reviews') }}</p>
                            <p class="text-3xl font-bold text-black">{{ $stats['total_reviews'] }}</p>
                        </div>
                        <div class="text-4xl">💬</div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">{{ __('manager.rating') }}</p>
                            <p class="text-3xl font-bold text-black">{{ number_format($stats['average_rating'], 1) }}</p>
                        </div>
                        <div class="text-4xl">⭐</div>
                    </div>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🏠 {{ __('manager.room_management') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('manager.add_edit_manage_rooms') }}</p>
                    <a href="{{ route('admin.rooms.index') }}?hotel_id={{ $hotel->id }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        {{ __('manager.manage_rooms') }}
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">📋 {{ __('manager.bookings') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('manager.view_manage_guest_bookings') }}</p>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        {{ __('manager.view_bookings') }}
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">⭐ {{ __('reviews.my_hotels_reviews') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('reviews.view_manage_hotel_reviews') }}</p>
                    <a href="{{ route('manager.reviews.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        {{ __('reviews.view_reviews') }}
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🏨 {{ __('manager.hotel_information') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('manager.update_hotel_information') }}</p>
                    <a href="{{ route('admin.hotels.edit', $hotel) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        {{ __('manager.edit_hotel') }}
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🛠️ {{ __('admin.facilities') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('admin.manage_facilities') }}</p>
                    <a href="{{ route('manager.facilities.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        {{ __('admin.manage_facilities') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection