@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('manager.facilities.index') }}" class="text-gray-600 hover:text-gray-800">← {{ __('admin.back_to_facilities') }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.facility_details') }}</h1>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.id') }}</h3>
                            <p class="text-gray-600">#{{ $facility->id }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.title') }}</h3>
                            <p class="text-gray-600">{{ __($facility->title) }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.created_at') }}</h3>
                            <p class="text-gray-600">{{ $facility->created_at }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.updated_at') }}</h3>
                            <p class="text-gray-600">{{ $facility->updated_at }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('manager.facilities.edit', $facility) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            {{ __('admin.edit') }}
                        </a>
                        <form method="POST" action="{{ route('manager.facilities.destroy', $facility) }}" class="inline" onsubmit="return confirm('{{ __('admin.are_you_sure') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                {{ __('admin.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection