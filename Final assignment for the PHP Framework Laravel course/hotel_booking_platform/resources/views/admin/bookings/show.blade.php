@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800">←
                        {{ __('admin.back_to_bookings') }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">📅 {{ __('booking.booking_details') }} #{{ $booking->id }}</h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">{{ __('admin.user_information') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('admin.user') }}:</span> <span class="text-gray-900">{{ $booking->user->full_name }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('admin.email') }}:</span> <span class="text-gray-900">{{ $booking->user->email }}</span></p>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mt-6">{{ __('booking.booking_details') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.hotel') }}:</span> <span class="text-gray-900">{{ $booking->room->hotel->title }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.address') }}:</span> <span class="text-gray-900">{{ $booking->room->hotel->address }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.room') }}:</span> <span class="text-gray-900">{{ $booking->room->title }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.room_type') }}:</span> <span class="text-gray-900">{{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.floor_area') }}:</span> <span class="text-gray-900">{{ $booking->room->floor_area }} {{ __('booking.square_meters') }}</span></p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">{{ __('booking.dates_and_guests') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.check_in_date') }}:</span> <span class="text-gray-900">{{ \App\Helpers\TranslationHelper::formatDate($booking->started_at) }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.check_out_date') }}:</span> <span class="text-gray-900">{{ \App\Helpers\TranslationHelper::formatDate($booking->finished_at) }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.number_of_nights') }}:</span> <span class="text-gray-900">{{ $booking->days }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.adults') }}:</span> <span class="text-gray-900">{{ $booking->adults ?? 1 }}</span></p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.children') }}:</span> <span class="text-gray-900">{{ $booking->children ?? 0 }}</span></p>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mt-6">{{ __('booking.pricing') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.price_per_night') }}:</span> <span class="text-gray-900">{{ \App\Helpers\TranslationHelper::formatPrice($booking->room->price) }}</span></p>
                                <p class="text-base"><span class="font-semibold text-gray-700">{{ __('booking.total_cost') }}:</span> <span class="text-gray-900 font-bold text-lg">{{ \App\Helpers\TranslationHelper::formatPrice($booking->price) }}</span></p>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mt-6">{{ __('admin.status') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm">
                                    <span class="font-medium text-gray-700">{{ __('booking.status') }}:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ \App\Helpers\TranslationHelper::translateBookingStatus($booking->status) }}
                                    </span>
                                </p>
                                <p class="text-sm"><span class="font-medium text-gray-700">{{ __('booking.booking_date') }}:</span> <span class="text-gray-900">{{ \App\Helpers\TranslationHelper::formatDateTime($booking->created_at) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
