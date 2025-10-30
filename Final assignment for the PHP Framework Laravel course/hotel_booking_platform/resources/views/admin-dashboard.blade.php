@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-5xl font-black mb-4 flex items-center">
                    <span class="text-5xl mr-4">📊</span>
                    <span
                        class="bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">{{ __('admin.admin_panel') }}</span>
                </h1>
                <p class="text-slate-300 text-xl">{{ __('admin.hotel_booking_system_overview') }}</p>
            </div>

            <!-- Stats Cards - Адаптивная сетка -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 lg:gap-6 xl:gap-8 mb-12">
                <a href="{{ route('admin.hotels.index') }}"
                    class="group relative bg-gradient-to-br from-blue-500/20 to-cyan-500/20 backdrop-blur-sm rounded-3xl p-4 lg:p-6 xl:p-8 border border-white/10 hover:border-white/20 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/25">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative">
                        <!-- Мобильная версия -->
                        <div class="flex items-center justify-between mb-2 lg:mb-4 lg:hidden">
                            <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg">
                                <span class="text-xl">🏨</span>
                            </div>
                            <div class="text-right min-w-0 flex-1 ml-2">
                                <p class="text-blue-300 font-medium text-xs truncate">{{ __('admin.hotels') }}</p>
                                <p class="text-2xl font-black text-black truncate">{{ $stats['hotels_count'] }}</p>
                            </div>
                        </div>

                        <!-- Десктопная версия -->
                        <div class="hidden lg:flex lg:flex-col lg:items-center lg:text-center">
                            <div class="p-4 xl:p-6 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl shadow-lg mb-4">
                                <span class="text-4xl xl:text-5xl">🏨</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-blue-300 font-medium text-sm xl:text-base">{{ __('admin.hotels') }}</p>
                                <p class="text-3xl xl:text-4xl font-black text-black">{{ $stats['hotels_count'] }}</p>
                                <p class="text-xs xl:text-sm text-gray-300 opacity-75">{{ __('admin.add_edit_hotels') }}</p>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full"></div>
                    </div>
                </a>

                <a href="{{ route('admin.rooms.index') }}"
                    class="group relative bg-gradient-to-br from-emerald-500/20 to-teal-500/20 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-white/20 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/25">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative">
                        <!-- Мобильная версия -->
                        <div class="flex items-center justify-between mb-2 lg:mb-4 lg:hidden">
                            <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl shadow-lg">
                                <span class="text-xl">🚪</span>
                            </div>
                            <div class="text-right min-w-0 flex-1 ml-2">
                                <p class="text-emerald-300 font-medium text-xs truncate">{{ __('admin.rooms') }}</p>
                                <p class="text-2xl font-black text-black truncate">{{ $stats['rooms_count'] }}</p>
                            </div>
                        </div>

                        <!-- Десктопная версия -->
                        <div class="hidden lg:flex lg:flex-col lg:items-center lg:text-center">
                            <div
                                class="p-4 xl:p-6 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl shadow-lg mb-4">
                                <span class="text-4xl xl:text-5xl">🚪</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-emerald-300 font-medium text-sm xl:text-base">{{ __('admin.rooms') }}</p>
                                <p class="text-3xl xl:text-4xl font-black text-black">{{ $stats['rooms_count'] }}</p>
                                <p class="text-xs xl:text-sm text-gray-300 opacity-75">{{ __('admin.add_edit_rooms') }}</p>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full"></div>
                    </div>
                </a>

                <a href="{{ route('admin.bookings.index') }}"
                    class="group relative bg-gradient-to-br from-purple-500/20 to-pink-500/20 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-white/20 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/25">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative">
                        <!-- Мобильная версия -->
                        <div class="flex items-center justify-between mb-2 lg:mb-4 lg:hidden">
                            <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg">
                                <span class="text-xl">📅</span>
                            </div>
                            <div class="text-right min-w-0 flex-1 ml-2">
                                <p class="text-purple-300 font-medium text-xs truncate">{{ __('admin.bookings') }}</p>
                                <p class="text-2xl font-black text-black truncate">{{ $stats['bookings_count'] }}</p>
                            </div>
                        </div>

                        <!-- Десктопная версия -->
                        <div class="hidden lg:flex lg:flex-col lg:items-center lg:text-center">
                            <div
                                class="p-4 xl:p-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl shadow-lg mb-4">
                                <span class="text-4xl xl:text-5xl">📅</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-purple-300 font-medium text-sm xl:text-base">{{ __('admin.bookings') }}</p>
                                <p class="text-3xl xl:text-4xl font-black text-black">{{ $stats['bookings_count'] }}</p>
                                <p class="text-xs xl:text-sm text-gray-300 opacity-75">{{ __('admin.track_bookings') }}</p>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="group relative bg-gradient-to-br from-orange-500/20 to-red-500/20 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-white/20 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/25">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative">
                        <!-- Мобильная версия -->
                        <div class="flex items-center justify-between mb-2 lg:mb-4 lg:hidden">
                            <div class="p-2 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg">
                                <span class="text-xl">👥</span>
                            </div>
                            <div class="text-right min-w-0 flex-1 ml-2">
                                <p class="text-orange-300 font-medium text-xs truncate">{{ __('admin.users') }}</p>
                                <p class="text-2xl font-black text-black truncate">{{ $stats['users_count'] }}</p>
                            </div>
                        </div>

                        <!-- Десктопная версия -->
                        <div class="hidden lg:flex lg:flex-col lg:items-center lg:text-center">
                            <div class="p-4 xl:p-6 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl shadow-lg mb-4">
                                <span class="text-4xl xl:text-5xl">👥</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-orange-300 font-medium text-sm xl:text-base">{{ __('admin.users') }}</p>
                                <p class="text-3xl xl:text-4xl font-black text-black">{{ $stats['users_count'] }}</p>
                                <p class="text-xs xl:text-sm text-gray-300 opacity-75">{{ __('admin.manage_users') }}</p>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-orange-500 to-red-500 rounded-full"></div>
                    </div>
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                    class="group relative bg-gradient-to-br from-yellow-500/20 to-amber-500/20 backdrop-blur-sm rounded-3xl p-8 border border-white/10 hover:border-white/20 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/25">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-amber-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative">
                        <!-- Мобильная версия -->
                        <div class="flex items-center justify-between mb-2 lg:mb-4 lg:hidden">
                            <div class="p-2 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl shadow-lg">
                                <span class="text-xl">⭐</span>
                            </div>
                            <div class="text-right min-w-0 flex-1 ml-2">
                                <p class="text-yellow-300 font-medium text-xs truncate">
                                    {{ __('reviews.reviews_management') }}
                                </p>
                                <p class="text-2xl font-black text-black truncate">{{ $stats['reviews_count'] ?? 0 }}</p>
                            </div>
                        </div>

                        <!-- Десктопная версия -->
                        <div class="hidden lg:flex lg:flex-col lg:items-center lg:text-center">
                            <div
                                class="p-4 xl:p-6 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-2xl shadow-lg mb-4">
                                <span class="text-4xl xl:text-5xl">⭐</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-yellow-300 font-medium text-sm xl:text-base">
                                    {{ __('reviews.reviews_management') }}
                                </p>
                                <p class="text-3xl xl:text-4xl font-black text-black">{{ $stats['reviews_count'] ?? 0 }}</p>
                                <p class="text-xs xl:text-sm text-gray-300 opacity-75">{{ __('admin.moderate_reviews') }}
                                </p>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-yellow-500 to-amber-500 rounded-full"></div>
                    </div>
                </a>

                <a href="{{ route('admin.system.status') }}"
                    class="group relative bg-gradient-to-br from-indigo-500/20 to-violet-500/20 backdrop-blur-sm rounded-3xl p-4 lg:p-6 xl:p-8 border border-white/10 hover:border-white/20 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-indigo-500/25">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-violet-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div class="relative">
                        <!-- Мобильная версия -->
                        <div class="flex items-center justify-between mb-2 lg:mb-4 lg:hidden">
                            <div class="p-2 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl shadow-lg">
                                <span class="text-xl">⚙️</span>
                            </div>
                            <div class="text-right min-w-0 flex-1 ml-2">
                                <p class="text-indigo-300 font-medium text-xs">{{ __('system.system_status') }}</p>
                                <p class="text-lg font-black text-black">{{ __('admin.system_status') }}</p>
                            </div>
                        </div>

                        <!-- Десктопная версия -->
                        <div class="hidden lg:flex lg:flex-col lg:items-center lg:text-center">
                            <div
                                class="p-4 xl:p-6 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-2xl shadow-lg mb-4">
                                <span class="text-4xl xl:text-5xl">⚙️</span>
                            </div>
                            <div class="space-y-2 w-full">
                                <p class="text-indigo-300 font-medium text-sm xl:text-base">
                                    {{ __('system.system_status') }}
                                </p>
                                
                                <div class="h-8">
                                    <svg viewBox="0 0 150 20" preserveAspectRatio="xMidYMid meet" class="w-full h-full">
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
                                            class="text-2xl xl:text-3xl font-black fill-current text-black" textLength="140"
                                            lengthAdjust="spacingAndGlyphs">
                                            {{ __('admin.system_status') }}
                                        </text>
                                    </svg>
                                </div>
                                <p class="text-xs xl:text-sm text-gray-300 opacity-75">
                                    {{ __('system.view_system_information') }}
                                </p>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full"></div>
                    </div>
                </a>
            </div>



            <!-- Recent Bookings -->
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl border border-white/20 overflow-hidden">
                <div class="px-8 py-6 border-b border-white/10">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <div class="w-2 h-8 bg-gradient-to-b from-blue-400 to-purple-400 rounded-full mr-4"></div>
                        {{ __('admin.recent_bookings') }}
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-800/50 to-slate-700/50">
                                <th class="px-6 py-4 text-left text-sm font-bold text-slate-300 uppercase tracking-wider">ID
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-slate-300 uppercase tracking-wider">
                                    {{ __('admin.user') }}
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-slate-300 uppercase tracking-wider">
                                    {{ __('admin.hotel') }}
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-slate-300 uppercase tracking-wider">
                                    {{ __('admin.room') }}
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-slate-300 uppercase tracking-wider">
                                    {{ __('admin.price') }}
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-slate-300 uppercase tracking-wider">
                                    {{ __('admin.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($recent_bookings as $booking)
                                <tr class="hover:bg-white/5 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-blue-400">#{{ $booking->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-300">
                                        {{ $booking->user->full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                        {{ $booking->room->hotel->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                        {{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="text-sm font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">{{ \App\Helpers\TranslationHelper::formatPrice($booking->price) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 text-black shadow-lg">
                                            {{ \App\Helpers\TranslationHelper::translateBookingStatus($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection