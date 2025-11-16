@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('manager.facilities.index') }}" class="text-gray-600 hover:text-gray-800">← {{ __('admin.back_to_facilities') }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ {{ __('admin.edit_facility') }}</h1>
                <p class="text-gray-600">{{ __('admin.update_facility_details') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form method="POST" action="{{ route('manager.facilities.update', $facility) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.facility_title') }} *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $facility->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('manager.facilities.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            {{ __('admin.update_facility') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection