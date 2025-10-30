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
                    <h1 class="text-3xl font-bold text-gray-900">🏨 {{ __('admin.hotel_management') }}</h1>
                    <p class="text-gray-600">{{ __('admin.add_edit_delete_hotels') }}</p>
                </div>
                <a href="{{ route('admin.hotels.create') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    {{ __('admin.add_hotel_button') }}
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed; width: 100%;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="width: 80px;">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="width: 320px;">{{ __('admin.name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="width: 280px;">{{ __('admin.location') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="width: 100px;">{{ __('admin.rooms') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="width: 200px;">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($hotels as $hotel)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $hotel->id }}
                                    </td>
                                    <td class="px-6 py-4" style="width: 320px;">
                                        <div class="flex items-center">
                                            <img class="h-10 w-10 rounded-lg object-cover flex-shrink-0"
                                                src="{{ $hotel->poster_url }}" alt="{{ $hotel->title }}">
                                            <div class="ml-3 min-w-0 overflow-hidden">
                                                <div class="text-sm font-medium text-gray-900 break-words leading-tight max-h-10 overflow-hidden"
                                                    title="{{ $hotel->title }}">{{ $hotel->title }}</div>
                                                <div class="text-xs text-gray-500 break-words leading-tight" title="{{ $hotel->description }}">
                                                    {{ Str::limit($hotel->description, 45) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900" style="width: 280px;">
                                        <div class="break-words leading-tight" title="{{ $hotel->address }}">{{ Str::limit($hotel->address, 50) }}</div>
                                        <div class="text-gray-500 break-words leading-tight" title="{{ $hotel->country }}">{{ $hotel->country }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $hotel->rooms->count() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-1">
                                            <a href="{{ route('admin.hotels.show', $hotel) }}"
                                                class="text-blue-600 hover:text-blue-900">{{ __('admin.view') }}</a>
                                            <a href="{{ route('admin.hotels.edit', $hotel) }}"
                                                class="text-yellow-600 hover:text-yellow-900">{{ __('admin.edit') }}</a>
                                            <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-left"
                                                    onclick="return confirm('{{ __('admin.are_you_sure') }}')">{{ __('admin.delete') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- {{ __('admin.pagination') }} -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $hotels->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection