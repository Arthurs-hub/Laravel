@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <a href="{{ route('manager.dashboard') }}" class="text-gray-600 hover:text-gray-800">← {{ __('admin.back_to_dashboard') }}</a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.facility_management') }}</h1>
                <p class="text-gray-600">{{ __('admin.add_edit_delete_facilities') }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin.facilities') }}</h2>
                    <a href="{{ route('manager.facilities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ __('admin.add_facility_button') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.id') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.title') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($facilities as $facility)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $facility->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ __($facility->title) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('manager.facilities.show', $facility) }}" class="text-blue-600 hover:text-blue-900">{{ __('admin.view') }}</a>
                                        <a href="{{ route('manager.facilities.edit', $facility) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('admin.edit') }}</a>
                                        <form method="POST" action="{{ route('manager.facilities.destroy', $facility) }}" class="inline" onsubmit="return confirm('{{ __('admin.are_you_sure') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('admin.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $facilities->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection