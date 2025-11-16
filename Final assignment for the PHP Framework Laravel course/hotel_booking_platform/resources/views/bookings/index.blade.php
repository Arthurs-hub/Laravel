@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-900">📋 {{ __('bookings.my_bookings') }}</h1>
            <div class="overflow-hidden">
                @if($bookings->isNotEmpty())
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $booking->room->hotel->title }}</h3>
                            <p class="text-gray-600 mb-2">{{ __('bookings.room') }}:
                                {{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}
                            </p>
                            <p class="text-gray-600 mb-2">{{ __('bookings.dates') }}:
                                {{ \App\Helpers\TranslationHelper::formatDate($booking->started_at) }} -
                                {{ \App\Helpers\TranslationHelper::formatDate($booking->finished_at) }}
                            </p>
                            <p class="text-gray-600 mb-4">{{ __('bookings.price') }}:
                                {{ \App\Helpers\TranslationHelper::formatPrice($booking->price) }}
                            </p>
                            <a href="{{ route('bookings.show', $booking) }}"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{ __('bookings.details') }}</a>
                        </div>
                    @endforeach
                @else
                    <h1 class="text-lg md:text-xl font-semibold text-gray-800">{{ __('bookings.no_bookings') }}</h1>
                @endif
            </div>
        </div>
    </div>
@endsection