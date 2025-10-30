@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🚪
                        {{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }}</h1>
                    <p class="text-gray-600">{{ __('admin.room_details') }}</p>
                </div>
                <div class="space-x-4">
                    <a href="{{ route('admin.rooms.edit', $room) }}"
                        class="bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-700 transition-colors">
                        {{ __('admin.edit') }}
                    </a>
                    <a href="{{ route('admin.rooms.index') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                        {{ __('admin.back_to_list') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.main_information') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">ID</label>
                                <p class="text-gray-900">#{{ $room->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.hotel') }}</label>
                                <p class="text-gray-900">{{ $room->hotel->title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.room_type') }}</label>
                                <p class="text-gray-900">{{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.floor_area') }}</label>
                                <p class="text-gray-900">{{ $room->floor_area }} {{ __('admin.square_meters') }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500">{{ __('admin.price_per_night') }}</label>
                                <p class="text-blue-600 font-semibold text-lg">
                                    {{ \App\Helpers\TranslationHelper::formatPrice($room->price) }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.description') }}</label>
                                <p class="text-gray-900">{{ \App\Helpers\TranslationHelper::translateRoomDescription($room->description) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.bookings') }}
                            ({{ $room->bookings->count() }})</h2>
                        @if($room->bookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($room->bookings->take(5) as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $booking->user->full_name }}</h3>
                                                <p class="text-gray-600 text-sm">
                                                    {{ \App\Helpers\TranslationHelper::formatDate($booking->started_at) }} -
                                                    {{ \App\Helpers\TranslationHelper::formatDate($booking->finished_at) }}</p>
                                                <p class="text-blue-600 font-semibold">{{ \App\Helpers\TranslationHelper::formatPrice($booking->price) }}</p>
                                            </div>
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ \App\Helpers\TranslationHelper::translateBookingStatus($booking->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">{{ __('admin.no_bookings_for_this_room') }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.image') }}</h2>
                        <img src="{{ $room->poster_url }}" alt="{{ $room->title }}" class="w-full rounded-lg object-cover">
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.statistics') }}</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.total_bookings') }}</span>
                                <span class="font-semibold">{{ $room->bookings->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.created') }}</span>
                                <span class="font-semibold">{{ $room->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection