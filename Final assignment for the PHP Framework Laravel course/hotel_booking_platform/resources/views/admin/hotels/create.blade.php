@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.add_hotel') }}</h1>
                <p class="text-gray-600">{{ __('admin.create_new_hotel') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <form method="POST" action="{{ route('admin.hotels.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.hotel_name') }}</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('title')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="country"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.country') }}</label>
                            <input id="country" type="text" name="country" value="{{ old('country') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('country')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address"
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.address') }}</label>
                        <input id="address" type="text" name="address" value="{{ old('address') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('address')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.description') }}</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="poster_url"
                                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.image_url') }}</label>
                            <input id="poster_url" type="url" name="poster_url" value="{{ old('poster_url') }}"
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
                                    <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->full_name }} ({{ $manager->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6">
                        <a href="{{ route('admin.hotels.index') }}"
                            class="text-gray-600 hover:text-gray-800">{{ __('admin.back_to_list') }}</a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                            {{ __('admin.create_hotel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection