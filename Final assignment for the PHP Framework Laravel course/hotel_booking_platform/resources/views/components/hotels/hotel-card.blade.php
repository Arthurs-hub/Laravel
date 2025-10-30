@props(['hotel'])

<div
    class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden group border border-gray-100 p-4 h-full flex flex-col">
    <a href="{{ route('hotels.show', $hotel) }}" class="flex-1 flex flex-col">
        <div class="relative mb-4 photo-container transition-all duration-300 hidden md:block" style="height: 192px;"
            onmouseover="this.style.height='450px'" onmouseout="this.style.height='192px'">
            <div class="h-full overflow-hidden rounded-2xl flex items-center justify-center bg-gray-100">
                <img class="w-full h-full object-contain transition-transform duration-300 hotel-image"
                    style="max-height: 100%; width: auto;" src="{{ $hotel->poster_url }}" alt="{{ $hotel->title }}">
            </div>

            <!-- Страна для десктопа -->
            <div
                class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ $hotel->country }}
            </div>
        </div>

        <!-- Мобильная версия изображения без hover эффектов -->
        <div class="relative mb-4 md:hidden" style="height: 192px;">
            <div class="h-full overflow-hidden rounded-2xl flex items-center justify-center bg-gray-100">
                <img class="w-full h-full object-contain" style="max-height: 100%; width: auto;"
                    src="{{ $hotel->poster_url }}" alt="{{ $hotel->title }}">
            </div>

            <!-- Страна для мобильных -->
            <div
                class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ $hotel->country }}
            </div>
        </div>

        <!-- Рейтинг под фото -->
        @if(isset($hotel->rating) && $hotel->rating > 0)
            <div class="flex justify-end mb-2">
                <div class="bg-blue-600 text-white px-2 py-1 rounded-lg text-sm font-semibold">
                    ⭐ {{ number_format($hotel->rating, 1) }}
                </div>
            </div>
        @endif

        <div class="px-2 flex-1 flex flex-col justify-between">
            <div class="mb-2">
                <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-2">{{ $hotel->title }}</h3>
                <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ Str::limit($hotel->address, 40) }}
                </p>
            </div>

            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                {{ Str::limit(\App\Http\Controllers\HotelDescriptionTranslator::translate($hotel->title, $hotel->description), 80) }}
            </p>

            <!-- Удобства -->
            @if($hotel->facilities->count() > 0)
                <div class="mb-3">
                    <div class="flex flex-wrap gap-1">
                        @foreach($hotel->facilities->take(3) as $facility)
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs font-medium">
                                {{ \App\Http\Controllers\TranslationHelper::translateFacility($facility->title) }}
                            </span>
                        @endforeach
                        @if($hotel->facilities->count() > 3)
                            <span class="text-xs text-gray-500">+{{ $hotel->facilities->count() - 3 }} {{ __('more') }}</span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Цена и кнопки - адаптивная версия -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2">
                <!-- Цена -->
                <div class="flex flex-col">
                    @if($hotel->rooms->isNotEmpty())
                        <div class="flex items-baseline">
                            <span
                                class="text-2xl font-bold text-gray-900">{{ \App\Http\Controllers\TranslationHelper::formatPrice($hotel->min_price) }}</span>
                            <span class="text-sm text-gray-600 ml-1">{{ __('/ night') }}</span>
                        </div>
                        @if($hotel->min_price != $hotel->max_price)
                            <span class="text-xs text-gray-500">{{ __('to') }}
                                {{ \App\Http\Controllers\TranslationHelper::formatPrice($hotel->max_price) }}</span>
                        @endif
                    @endif
                </div>

                <!-- Все кнопки в одном ряду - адаптивные -->
                <div class="flex items-center gap-1 min-w-0 flex-1 sm:justify-end justify-center">
                    <!-- Кнопка просмотра отзывов -->
                    <button type="button"
                        class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-black font-medium rounded border border-blue-300 shadow-sm transition-all duration-200 review-view-btn flex-shrink-0 text-xs"
                        style="white-space: nowrap; min-width: fit-content;" data-type="hotel"
                        data-id="{{ $hotel->id }}" data-title="{{ $hotel->title }}"
                        onclick="event.preventDefault(); event.stopPropagation();">
                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="truncate">{{ __('reviews.view_reviews_short') }}</span>
                    </button>

                    <!-- Кнопка оставить отзыв -->
                    <button type="button"
                        class="inline-flex items-center px-2 py-1 bg-green-100 hover:bg-green-200 text-black font-medium rounded border border-green-300 shadow-sm transition-all duration-200 review-leave-btn flex-shrink-0 text-xs"
                        style="white-space: nowrap; min-width: fit-content;" data-type="hotel"
                        data-id="{{ $hotel->id }}" data-title="{{ $hotel->title }}"
                        onclick="event.preventDefault(); event.stopPropagation();">
                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="truncate">{{ __('reviews.leave_review_short') }}</span>
                    </button>

                    <!-- Кнопка подробнее -->
                    <div class="inline-flex items-center px-2 py-1 bg-purple-100 hover:bg-purple-200 text-black font-medium rounded border border-purple-300 shadow-sm transition-all duration-200 flex-shrink-0 text-xs"
                        style="white-space: nowrap; min-width: fit-content;">
                        <span class="truncate">{{ __('Details') }}</span>
                        <svg class="w-3 h-3 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>