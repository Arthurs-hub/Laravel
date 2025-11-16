@extends('layouts.app')

@section('content')
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        <div class="flex justify-start item-start space-y-2 flex-col">
            <h1 class="text-3xl lg:text-4xl font-semibold leading-7 lg:leading-9 text-gray-800">{{ __('booking.hotel') }}
                {{ $booking->room->hotel->title }}
            </h1>
        </div>
        <div class="mt-10 bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <h3 class="text-xl font-semibold mb-4">{{ __('booking.booking_details') }}</h3>
                    <div class="space-y-3">
                        <p class="text-lg"><strong>{{ __('booking.hotel') }}:</strong> {{ $booking->room->hotel->title }}
                        </p>
                        <p><strong>{{ __('booking.address') }}:</strong> {{ $booking->room->hotel->address }}</p>
                        <p><strong>{{ __('booking.room') }}:</strong> {{ \App\Helpers\TranslationHelper::translateRoomTitle($booking->room->title) }}</p>
                        <p><strong>{{ __('booking.room_type') }}:</strong>
                            {{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}</p>
                        <p><strong>{{ __('booking.room_description') }}:</strong>
                            {{ __('room.comfortable_room_description') }}</p>
                        <p><strong>{{ __('booking.floor_area') }}:</strong> {{ $booking->room->floor_area }}
                            {{ __('booking.square_meters') }}
                        </p>
                        <p><strong>{{ __('booking.check_in_date') }}:</strong>
                            {{ \App\Helpers\TranslationHelper::formatDate($booking->started_at) }}
                        </p>
                        <p><strong>{{ __('booking.check_out_date') }}:</strong>
                            {{ \App\Helpers\TranslationHelper::formatDate($booking->finished_at) }}
                        </p>
                        <p><strong>{{ __('booking.number_of_nights') }}:</strong> {{ $booking->days }}</p>
                        <p><strong>{{ __('booking.adults') }}:</strong> {{ $booking->adults ?? 1 }}</p>
                        <p><strong>{{ __('booking.children') }}:</strong> {{ $booking->children ?? 0 }}</p>
                        <p><strong>{{ __('booking.price_per_night') }}:</strong>
                            {{ \App\Helpers\TranslationHelper::formatPrice($booking->room->price) }}</p>
                        <p class="text-lg"><strong>{{ __('booking.total_cost') }}:</strong>
                            {{ \App\Helpers\TranslationHelper::formatPrice($booking->price) }}</p>
                        <p><strong>{{ __('booking.status') }}:</strong> <span
                                class="text-green-600 font-medium">{{ __('booking.status_confirmed') }}</span>
                        </p>
                        <p><strong>{{ __('booking.booking_date') }}:</strong>
                            {{ \App\Helpers\TranslationHelper::formatDateTime($booking->created_at) }}
                        </p>
                    </div>
                </div>
                <div class="ml-6 flex flex-col space-y-2">
                    <a href="{{ route('bookings.edit', $booking) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-center whitespace-nowrap">
                        {{ __('booking.edit_booking') }}
                    </a>
                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors whitespace-nowrap"
                            onclick="return confirm('{{ __('booking.cancel_booking_confirm') }}')">
                            {{ __('booking.cancel_booking') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection