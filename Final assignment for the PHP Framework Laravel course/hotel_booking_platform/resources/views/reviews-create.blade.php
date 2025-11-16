@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">✍️ {{ __('reviews.leave_a_review') }}</h1>

                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-8">
                        <h3 class="font-semibold text-lg mb-2">{{ $booking->room->hotel->title }}</h3>
                        <p class="text-gray-600">
                            {{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            {{ __('reviews.period') }}: {{ $booking->started_at->format('d.m.Y') }} -
                            {{ $booking->finished_at->format('d.m.Y') }}
                        </p>
                    </div>

                    <form action="{{ route('reviews.store', $booking) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">⭐ {{ __('reviews.rating') }}</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" required>
                                        <div
                                            class="w-12 h-12 rounded-full border-2 border-gray-300 flex items-center justify-center text-2xl peer-checked:border-yellow-400 peer-checked:bg-yellow-50 hover:border-yellow-300 transition-colors">
                                            {{ $i }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">💬
                                {{ __('reviews.comment') }}</label>
                            <textarea name="comment" id="comment" rows="5"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="{{ __('reviews.share_your_experience') }}"
                                required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                📝 {{ __('reviews.submit_review') }}
                            </button>
                            <a href="{{ route('bookings.index') }}"
                                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-colors text-center">
                                ❌ {{ __('reviews.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection