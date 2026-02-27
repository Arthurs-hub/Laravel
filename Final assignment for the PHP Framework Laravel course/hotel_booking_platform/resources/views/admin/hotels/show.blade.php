@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок и кнопки - адаптивная версия -->
            <div class="mb-8">
                <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">🏨 {{ $hotel->title }}</h1>
                        <p class="text-gray-600">{{ __('admin.hotel_details') }}</p>
                    </div>

                    <!-- Кнопки - вертикально на мобильных, горизонтально на десктопе -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('admin.hotels.edit', $hotel) }}"
                            class="w-full sm:w-auto text-center bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-700 transition-colors">
                            {{ __('admin.edit') }}
                        </a>
                        <a href="{{ route('admin.hotels.index') }}"
                            class="w-full sm:w-auto text-center bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                            {{ __('admin.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Hotel Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.main_information') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">ID</label>
                                <p class="text-gray-900">#{{ $hotel->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.country') }}</label>
                                <p class="text-gray-900">{{ $hotel->country }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.address') }}</label>
                                <p class="text-gray-900">{{ $hotel->address }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">{{ __('admin.description') }}</label>
                                <p class="text-gray-900">
                                    {{ \App\Helpers\TranslationHelper::translateHotelDescription($hotel->description) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Rooms -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ __('admin.rooms') }}
                                ({{ $hotel->rooms->count() }})</h2>
                            <a href="{{ route('admin.rooms.create') }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                {{ __('admin.add_room_button') }}
                            </a>
                        </div>
                        <div class="space-y-4">
                            @foreach($hotel->rooms as $room)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">
                                                {{ \App\Helpers\TranslationHelper::translateRoomType($room->title) }}
                                            </h3>
                                            <p class="text-gray-600 text-sm">
                                                {{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }}
                                            </p>
                                            <p class="text-blue-600 font-semibold">
                                                {{ \App\Helpers\TranslationHelper::formatPrice($room->price) }}
                                                {{ __('admin.per_night') }}
                                            </p>
                                        </div>
                                        <img src="{{ $room->poster_url }}" alt="{{ $room->title }}"
                                            crossorigin="anonymous"
                                            class="w-16 h-16 rounded-lg object-cover">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Hotel Image -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.image') }}</h2>
                        <img src="{{ $hotel->poster_url }}" alt="{{ $hotel->title }}"
                            crossorigin="anonymous"
                            class="w-full rounded-lg object-cover">
                    </div>

                    <!-- Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('admin.statistics') }}</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.total_rooms') }}</span>
                                <span class="font-semibold">{{ $hotel->rooms->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('admin.created') }}</span>
                                <span class="font-semibold">{{ $hotel->created_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection