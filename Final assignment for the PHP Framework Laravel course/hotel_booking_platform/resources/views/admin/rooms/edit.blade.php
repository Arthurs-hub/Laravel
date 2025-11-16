@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">🚪 {{ __('admin.edit_room') }}</h1>
                <p class="text-gray-600">{{ __('admin.update_room_info') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.room_name') }}</label>
                            <input id="title" type="text" name="title"
                                value="{{ old('title', \App\Helpers\TranslationHelper::translateRoomType($room->type)) }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('title')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="hotel_id"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.hotel') }}</label>
                            <select id="hotel_id" name="hotel_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ old('hotel_id', $room->hotel_id) == $hotel->id ? 'selected' : '' }}>
                                        {{ $hotel->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="type"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.room_type') }}</label>
                            <input id="type" type="text" name="type"
                                value="{{ old('type', \App\Helpers\TranslationHelper::translateRoomType($room->type)) }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('type')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="price"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.price_per_night') }}
                                ({{ \App\Helpers\TranslationHelper::getCurrencySymbol() }})</label>
                            <input id="price" type="number" name="price"
                                value="{{ old('price', \App\Helpers\TranslationHelper::convertPriceForEdit($room->price)) }}"
                                required min="0" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('price')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="floor_area"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.floor_area_m2') }}</label>
                            <input id="floor_area" type="number" name="floor_area"
                                value="{{ old('floor_area', $room->floor_area) }}" required min="0" step="0.1"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('floor_area')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.description') }}</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', \App\Helpers\TranslationHelper::translateRoomDescription($room->description)) }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="poster_url"
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.image_url') }}</label>
                        <input id="poster_url" type="url" name="poster_url"
                            value="{{ old('poster_url', $room->poster_url) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('poster_url')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Кнопки - адаптивная версия -->
                    <div class="pt-6 space-y-4">
                        <!-- Кнопка "Назад" - всегда сверху на мобильных -->
                        <div class="flex justify-start">
                            <a href="{{ route('admin.rooms.show', $room) }}"
                                class="text-gray-600 hover:text-gray-800 text-sm">{{ __('admin.back_to_room') }}</a>
                        </div>

                        <!-- Основные кнопки - вертикально на мобильных, горизонтально на десктопе -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                            <a href="{{ route('admin.rooms.index') }}"
                                class="w-full sm:w-auto text-center bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-colors">
                                {{ __('admin.cancel') }}
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                {{ __('admin.save_changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection