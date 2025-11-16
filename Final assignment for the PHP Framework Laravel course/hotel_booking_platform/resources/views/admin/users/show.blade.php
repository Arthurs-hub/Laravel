@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        @if($user->avatar)
                            <img class="h-16 w-16 rounded-full" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->full_name }}">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-700">{{ substr($user->full_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">👤 {{ $user->full_name }}</h1>
                        <p class="text-gray-600">{{ __('admin.user_details') }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                    {{ __('admin.back_to_list') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.personal_information') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">ID</label>
                                <p class="text-gray-900">#{{ $user->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.email') }}</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.phone') }}</label>
                                <p class="text-gray-900">{{ $user->phone }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ __('admin.date_of_birth') }}</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('d.m.Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.gender') }}</label>
                                <p class="text-gray-900">
                                    @if($user->gender === 'male')
                                        {{ __('admin.male') }}
                                    @elseif($user->gender === 'female')
                                        {{ __('admin.female') }}
                                    @else
                                        {{ __('profile.other') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.country') }}</label>
                                <p class="text-gray-900">{{ $user->country }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.city') }}</label>
                                <p class="text-gray-900">{{ $user->city }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.postal_code') }}</label>
                                <p class="text-gray-900">{{ $user->postal_code }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.address') }}</label>
                                <p class="text-gray-900">{{ $user->address }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.bookings') }}
                            ({{ $user->bookings->count() }})</h2>
                        @if($user->bookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($user->bookings as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $booking->room->hotel->title }}</h3>
                                                <p class="text-gray-600">{{ \App\Helpers\TranslationHelper::translateRoomTitle($booking->room->title) }}</p>
                                                <p class="text-gray-600 text-sm">
                                                    {{ \App\Helpers\TranslationHelper::formatDate($booking->started_at) }} -
                                                    {{ \App\Helpers\TranslationHelper::formatDate($booking->finished_at) }}</p>
                                                <p class="text-blue-600 font-semibold">
                                                    @php
                                                        $locale = app()->getLocale();
                                                        $currency = match($locale) {
                                                            'ru' => '₽',
                                                            'ar' => 'د.إ',
                                                            'en' => '$',
                                                            default => '€'
                                                        };
                                                        $convertedPrice = match($locale) {
                                                            'ru' => $booking->price,
                                                            'ar' => $booking->price * 0.27,
                                                            'en' => $booking->price * 0.011,
                                                            default => $booking->price * 0.01
                                                        };
                                                    @endphp
                                                    {{ number_format($convertedPrice, 0, ',', ' ') }} {{ $currency }}
                                                </p>
                                            </div>
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $booking->status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('admin.no_bookings_for_this_user') }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.account_status') }}</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.role') }}</span>
                                @if($user->role === 'admin')
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ __('admin.administrator') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ __('admin.user') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.2fa_enabled') }}</span>
                                <span
                                    class="font-semibold">{{ $user->two_factor_enabled ? __('admin.yes') : __('admin.no') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.email_verified') }}</span>
                                <span
                                    class="font-semibold">{{ $user->email_verified ? __('admin.yes') : __('admin.no') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.registration') }}</span>
                                <span class="font-semibold">{{ $user->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.statistics') }}</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.total_bookings') }}</span>
                                <span class="font-semibold">{{ $user->bookings->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.total_amount') }}</span>
                                <span class="font-semibold text-blue-600">
                                    @php
                                        $locale = app()->getLocale();
                                        $currency = match($locale) {
                                            'ru' => '₽',
                                            'ar' => 'د.إ',
                                            'en' => '$',
                                            default => '€'
                                        };
                                        $totalPrice = $user->bookings->sum('price');
                                        $convertedTotal = match($locale) {
                                            'ru' => $totalPrice,
                                            'ar' => $totalPrice * 0.27,
                                            'en' => $totalPrice * 0.011,
                                            default => $totalPrice * 0.01
                                        };
                                    @endphp
                                    {{ number_format($convertedTotal, 0, ',', ' ') }} {{ $currency }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection