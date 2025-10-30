@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold mb-6">{{ __('booking.room_booking') }}</h1>

                    <!-- Hotel and Room Info -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <h2 class="text-xl font-semibold">{{ $room->hotel->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $room->hotel->address }}</p>
                        <div class="mt-2">
                            <h3 class="text-lg font-medium">
                                {{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ __('Comfortable room with all amenities') }}</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                                {{ \App\Helpers\TranslationHelper::formatPrice($room->price) }}
                                {{ __('booking.per_night') }}
                            </p>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form method="POST" action="{{ route('bookings.store') }}">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Check-in Date -->
                            <div>
                                <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('booking.check_in_date') }}
                                </label>
                                <input type="date" id="started_at" name="started_at" value="{{ old('started_at') }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                @error('started_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Check-out Date -->
                            <div>
                                <label for="finished_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('booking.check_out_date') }}
                                </label>
                                <input type="date" id="finished_at" name="finished_at" value="{{ old('finished_at') }}"
                                    min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                @error('finished_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Guests -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="adults" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('booking.adults') }}
                                </label>
                                <select id="adults" name="adults"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('adults', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('adults')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="children" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('booking.children') }}
                                </label>
                                <select id="children" name="children"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    @for($i = 0; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ old('children', 0) == $i ? 'selected' : '' }}>{{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error('children')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Price Calculation -->
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium">{{ __('booking.total_cost') }}</span>
                                <span id="total-price" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ __('booking.select_dates') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <span id="nights-count">0</span> {{ __('booking.nights') }} ×
                                <span
                                    id="price-per-night">{{ \App\Helpers\TranslationHelper::formatPrice($room->price) }}</span>
                            </p>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->has('booking_error'))
                            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ $errors->first('booking_error') }}
                            </div>
                        @endif

                        <!-- Back Button -->
                        <div class="mt-6 mb-4">
                            <a href="{{ route('hotels.show', $room->hotel) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← {{ __('booking.back_to_hotel') }}
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('booking.book') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var selectDatesText = '{{ __("booking.select_dates") }}';
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('started_at');
            const endDateInput = document.getElementById('finished_at');
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');
            const totalPriceElement = document.getElementById('total-price');
            const nightsCountElement = document.getElementById('nights-count');
            const pricePerNightElement = document.getElementById('price-per-night');
            const basePricePerNight = {{ $room->price }};
            const exchangeRate = {{ \App\Helpers\TranslationHelper::getExchangeRate() }};
            const currency = '{{ \App\Helpers\TranslationHelper::getCurrency() }}';

            function calculatePrice() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                const adults = parseInt(adultsInput.value) || 1;
                const children = parseInt(childrenInput.value) || 0;

                if (startDate && endDate && endDate > startDate) {
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    let pricePerNight = basePricePerNight;
                    if (adults > 2) {
                        pricePerNight += (adults - 2) * 1000;
                    }
                    if (children > 0) {
                        pricePerNight += children * 500;
                    }

                    // Конвертируем цену в соответствующую валюту
                    const convertedPricePerNight = Math.round(pricePerNight * exchangeRate);
                    const totalPrice = nights * convertedPricePerNight;

                    nightsCountElement.textContent = nights;
                    pricePerNightElement.textContent = new Intl.NumberFormat('ru-RU').format(convertedPricePerNight) + ' ' + currency;
                    totalPriceElement.textContent = new Intl.NumberFormat('ru-RU').format(totalPrice) + ' ' + currency;
                } else {
                    nightsCountElement.textContent = '0';
                    totalPriceElement.textContent = selectDatesText;
                }
            }

            startDateInput.addEventListener('change', function () {
                const startDate = new Date(this.value);
                startDate.setDate(startDate.getDate() + 1);
                endDateInput.min = startDate.toISOString().split('T')[0];
                calculatePrice();
            });

            endDateInput.addEventListener('change', calculatePrice);
            adultsInput.addEventListener('change', calculatePrice);
            childrenInput.addEventListener('change', calculatePrice);
        });
    </script>
@endsection