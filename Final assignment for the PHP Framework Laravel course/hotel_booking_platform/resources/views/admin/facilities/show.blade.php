@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('admin.facilities.index') }}" class="text-gray-600 hover:text-gray-800">←
                        {{ __('admin.back_to_facilities') }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.facility_details') }}</h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('admin.id') }}</label>
                        <p class="text-lg text-gray-900">#{{ $facility->id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('admin.title') }}</label>
                        <p class="text-lg text-gray-900">{{ $facility->title }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('admin.created_at') }}</label>
                        <p class="text-lg text-gray-900">{{ $facility->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">{{ __('admin.updated_at') }}</label>
                        <p class="text-lg text-gray-900">{{ $facility->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.facilities.edit', $facility) }}"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        {{ __('admin.edit') }}
                    </a>
                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            onclick="return confirm('{{ __('admin.are_you_sure') }}')">
                            {{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
