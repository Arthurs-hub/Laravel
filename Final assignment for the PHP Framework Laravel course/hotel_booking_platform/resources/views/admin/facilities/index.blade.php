@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">←
                            {{ __('admin.back_to_dashboard') }}</a>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.facility_management') }}</h1>
                    <p class="text-gray-600">{{ __('admin.add_edit_delete_facilities') }}</p>
                </div>
                <a href="{{ route('admin.facilities.create') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    {{ __('admin.add_facility_button') }}
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.title') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($facilities as $facility)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $facility->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ __($facility->title) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.facilities.show', $facility) }}"
                                                class="text-blue-600 hover:text-blue-900">{{ __('admin.view') }}</a>
                                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                                                class="text-yellow-600 hover:text-yellow-900">{{ __('admin.edit') }}</a>
                                            <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('{{ __('admin.are_you_sure') }}')">{{ __('admin.delete') }}</button>
                                            </form>
                                        </div>
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
