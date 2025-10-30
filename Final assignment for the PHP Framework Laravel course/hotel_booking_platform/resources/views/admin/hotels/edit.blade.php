@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.edit_hotel') }}</h1>
                <p class="text-gray-600">{{ __('admin.update_hotel_info') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form method="POST" action="{{ route('admin.hotels.update', $hotel) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.hotel_name') }}</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $hotel->title) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('title')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="country"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.country') }}</label>
                            <input id="country" type="text" name="country" value="{{ old('country', $hotel->country) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('country')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address"
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.address') }}</label>
                        <input id="address" type="text" name="address" value="{{ old('address', $hotel->address) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('address')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.description') }}</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', \App\Http\Controllers\HotelDescriptionTranslator::translate($hotel->title, $hotel->description)) }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="poster_url"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.image_url') }}</label>
                            <input id="poster_url" type="url" name="poster_url"
                                value="{{ old('poster_url', $hotel->poster_url) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('poster_url')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="manager_id"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.hotel_manager') }}</label>
                            <select id="manager_id" name="manager_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">{{ __('admin.not_assigned') }}</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ old('manager_id', $hotel->manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->full_name }} ({{ $manager->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Кнопки - адаптивная версия -->
                    <div class="pt-6 space-y-4">
                        <!-- Кнопка "Назад" - всегда сверху на мобильных -->
                        <div class="flex justify-start">
                            <a href="{{ route('admin.hotels.show', $hotel) }}"
                                class="text-gray-600 hover:text-gray-800 text-sm">{{ __('admin.back_to_hotel') }}</a>
                        </div>

                        <!-- Основные кнопки - вертикально на мобильных, горизонтально на десктопе -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                            <a href="{{ route('admin.hotels.index') }}"
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