@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Шапка отеля -->
        <div class="bg-white py-4 border-b">
            <div class="container mx-auto px-4">
                <div class="max-w-full relative">
                    <!-- Фото отеля - абсолютное позиционирование справа -->
                    <div class="hidden lg:block absolute right-0 top-0 z-10">
                        <img class="w-auto object-cover rounded-lg shadow-md" src="{{ $hotel->poster_url }}"
                            crossorigin="anonymous"
                            alt="{{ $hotel->title }}" style="height: 6cm;">
                    </div>

                    <!-- Контент слева -->
                    <div class="lg:pr-80">
                        <div class="flex items-center space-x-2 mb-4">
                            <span
                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $hotel->country }}</span>
                            @if($hotel->rating)
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    ⭐ {{ number_format($hotel->rating, 1) }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-bold mb-4 text-gray-900">{{ $hotel->title }}</h1>
                        <p class="text-xl text-gray-700 mb-2">📍 {{ $hotel->address }}</p>

                        <!-- Описание отеля - проходит под фото -->
                        @if($hotel->description && $hotel->description !== 'Luxury hotel with exceptional service and amenities')
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 mb-6"
                                style="margin-top: 0.5cm;">
                                <p class="text-lg text-gray-600 leading-relaxed">
                                    {{ \App\Http\Controllers\HotelDescriptionTranslator::translate($hotel->title, $hotel->description) }}
                                </p>
                            </div>
                        @endif

                        <!-- Удобства отеля -->
                        @if($hotel->facilities->count() > 0)
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">✨ {{ __('Hotel facilities') }}</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($hotel->facilities as $facility)
                                        <div class="flex items-center space-x-2 bg-white/60 rounded-lg p-2">
                                            <span class="text-blue-600">✓</span>
                                            <span
                                                class="text-sm text-gray-700">{{ \App\Helpers\TranslationHelper::translateFacility($facility->title) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Фото отеля для мобильных -->
                    <div class="lg:hidden mt-6">
                        <img class="w-full object-cover rounded-lg shadow-md" src="{{ $hotel->poster_url }}"
                            crossorigin="anonymous"
                            alt="{{ $hotel->title }}" style="max-height: 300px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Доступные номера -->
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col md:flex-row items-start justify-between mb-8">
                <!-- Левая колонка: кнопка назад и заголовок -->
                <div class="flex-shrink-0 flex flex-col">
                    <a href="{{ $backUrl }}"
                        class="inline-flex items-center w-auto self-start whitespace-nowrap px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-xl transition-colors mb-4">
                        ← {{ __('Back to hotels') }}
                    </a>
                    <div class="flex items-end space-x-4">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">🏨 {{ __('Available rooms') }}</h2>
                            <p class="text-gray-600">{{ __('Choose a suitable room for your stay') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Фильтр по удобствам справа -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-3 w-full md:w-auto md:flex-shrink-0"
                    style="height: 4cm; overflow-y: auto;">
                    <form method="GET" action="{{ route('hotels.show', $hotel) }}">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">✨ {{ __('Filter by room facilities') }}</h3>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            @php
                                $roomFacilities = \App\Models\Facility::whereIn('title', [
                                    'Мини-бар',
                                    'Сейф',
                                    'Балкон',
                                    'Кондиционер',
                                    'Чайник/кофеварка',
                                    'Рабочий стол',
                                    'Собственная ванная комната',
                                    'Телевизор с плоским экраном',
                                    'Бесплатный Wi-Fi в номере',
                                    'Шкаф или гардероб',
                                    'Фен',
                                    'Халат'
                                ])->get();
                            @endphp
                            @foreach($roomFacilities as $facility)
                                <label
                                    class="flex items-center bg-gray-50 rounded-lg p-2 cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                        @if(in_array($facility->id, (array) request('facilities', []))) checked @endif
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span
                                        class="text-xs text-gray-700 ml-2">{{ \App\Helpers\TranslationHelper::translateFacility($facility->title) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex space-x-2 items-end">
                            <button type="submit"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition-colors">
                                🔍 {{ __('Apply filter') }}
                            </button>
                            <a href="{{ route('hotels.show', $hotel) }}"
                                class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white font-medium text-xs rounded-lg transition-colors">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($availableRooms as $room)
                    <x-rooms.room-card :room="$room" />
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="text-6xl mb-4">🏨</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Номера не найдены</h3>
                        <p class="text-gray-600">Попробуйте изменить параметры фильтра</p>
                    </div>
                @endforelse
            </div>

            <!-- Отзывы -->
            @php
                $approvedReviews = collect();
                if (method_exists($hotel, 'reviews')) {
                    $approvedReviews = $hotel->reviews()->approved()->with('user')->latest()->take(5)->get();
                }
            @endphp
            @if($approvedReviews->count() > 0)
                <div class="bg-white/80 backdrop-blur-md rounded-3xl shadow-xl p-8 mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">💬 {{ __('Guest reviews') }}</h2>
                    <div class="space-y-6">
                        @foreach($approvedReviews as $review)
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($review->user->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $review->user->full_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $review->created_at->format('d.m.Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span
                                                class="text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">⭐</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-700">{{ $review->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleFacilities(button) {
            // Попробуем найти элемент как nextElementSibling (для room-card)
            let extraFacilities = button.nextElementSibling;

            // Если не найден, попробуем найти в родительском элементе (для других случаев)
            if (!extraFacilities || !extraFacilities.classList.contains('extra-facilities')) {
                extraFacilities = button.parentNode.querySelector('.extra-facilities');
            }

            if (extraFacilities && extraFacilities.classList.contains('hidden')) {
                extraFacilities.classList.remove('hidden');
                if (button.textContent.includes('+')) {
                    button.textContent = button.textContent.replace('+', '-');
                } else {
                    button.textContent = '{{ __("Hide") }}';
                }
            } else if (extraFacilities) {
                extraFacilities.classList.add('hidden');
                if (button.textContent.includes('-')) {
                    button.textContent = button.textContent.replace('-', '+');
                } else {
                    const count = extraFacilities.querySelectorAll('span').length;
                    button.textContent = `+${count} {{ __('more') }}`;
                }
            }
        }
    </script>

    <!-- Review modals for functionality -->
    <x-review-modals :base-url="url('/')" />
@endsection