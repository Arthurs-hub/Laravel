@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('admin.facilities.index') }}" class="text-gray-600 hover:text-gray-800">←
                        {{ __('admin.back_to_facilities') }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">➕ {{ __('admin.add_facility') }}</h1>
                <p class="text-gray-600">{{ __('admin.create_new_facility') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('admin.facilities.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.facility_title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            name="title" 
                            id="title" 
                            value="{{ old('title') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                            required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.facilities.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ __('admin.create_facility') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
