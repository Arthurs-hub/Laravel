@props(['room'])

@php
    // Если есть проверка для конкретных дат, используем её
    if (isset($room->is_available_for_dates)) {
        $isBooked = !$room->is_available_for_dates;
    } else {
        // Иначе проверяем общую доступность
        $isBooked = !$room->isAvailable();
    }
    $currentBooking = $room->getCurrentBooking();
@endphp

<div
    class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden group border border-gray-100 p-4 h-full flex flex-col relative">

    @if($isBooked)
        <!-- Фиолетовая лента для забронированных номеров -->
        <div
            class="absolute top-2 right-2 bg-purple-600 text-white px-4 py-2 rounded-full text-sm font-bold z-20 shadow-xl border-2 border-white">
            {{ __('Booked') }}
        </div>
    @endif

    <div class="relative mb-4 photo-container transition-all duration-300" style="height: 192px;"
        onmouseover="this.style.height='450px'" onmouseout="this.style.height='192px'">
        <div
            class="h-full overflow-hidden rounded-2xl flex items-center justify-center bg-gray-100 {{ $isBooked ? 'opacity-75' : '' }}">
            @php
                $roomPhotos = $room->room_photos;
            @endphp
            @if(count($roomPhotos) > 0)
                <img class="w-full h-full object-contain transition-transform duration-300"
                    style="max-height: 100%; width: auto;" src="{{ $roomPhotos[0] }}" alt="{{ $room->title }}">
            @else
                <img class="w-full h-full object-contain transition-transform duration-300"
                    style="max-height: 100%; width: auto;" src="{{ $room->poster_url }}" alt="{{ $room->title }}">
            @endif
        </div>
    </div>

    <div class="px-2 flex-1 flex flex-col justify-between">
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }}
                </h3>
                @if($room->floor_area)
                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                        📐 {{ $room->floor_area }} {{ __('admin.square_meters') }}
                    </span>
                @endif
            </div>
            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                {{ \App\Helpers\TranslationHelper::translateRoomDescription($room->type) }}</p>

            <!-- Удобства номера -->
            @if($room->facilities && $room->facilities->count() > 0)
                <div class="mb-3">
                    <div class="flex flex-wrap gap-1">
                        @foreach($room->facilities->take(4) as $facility)
                            <span class="bg-green-50 text-green-700 px-2 py-1 rounded-md text-xs font-medium">
                                {{ \App\Helpers\TranslationHelper::translateFacility($facility->title) }}
                            </span>
                        @endforeach
                        @if($room->facilities->count() > 4)
                            <button onclick="toggleFacilities(this)"
                                class="text-xs text-blue-600 hover:text-blue-800 cursor-pointer">+{{ $room->facilities->count() - 4 }}
                                {{ __('more') }}</button>
                            <div class="hidden mt-1 extra-facilities">
                                @foreach($room->facilities->skip(4) as $facility)
                                    <span
                                        class="bg-green-50 text-green-700 px-2 py-1 rounded-md text-xs font-medium mr-1 mb-1 inline-block">
                                        {{ \App\Helpers\TranslationHelper::translateFacility($facility->title) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Цена и кнопки - адаптивная версия -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <!-- Цена -->
            <div class="flex flex-col">
                <div class="flex items-baseline">
                    <span
                        class="text-2xl font-bold text-gray-900">{{ \App\Helpers\TranslationHelper::formatPrice($room->price) }}</span>
                    <span class="text-sm text-gray-600 ml-1">{{ __('/ night') }}</span>
                </div>
                @if(isset($room->total_price))
                    <div class="text-sm text-gray-500">
                        {{ __('room.total_for_nights', ['days' => $room->days]) }}:
                        <span
                            class="font-semibold">{{ \App\Helpers\TranslationHelper::formatPrice($room->total_price) }}</span>
                    </div>
                @endif
            </div>

            <!-- Кнопки - адаптивные -->
            <div class="flex items-center gap-1 min-w-0 flex-1 sm:justify-end justify-center flex-wrap">
                <!-- Кнопка просмотра отзывов -->
                <button type="button"
                    class="inline-flex items-center px-1 py-1 bg-blue-100 hover:bg-blue-200 text-black font-medium text-xs rounded border border-blue-300 shadow-sm transition-all duration-200 review-view-btn flex-shrink-0"
                    style="height: 0.8cm; font-size: 10px; white-space: nowrap;" data-type="room"
                    data-id="{{ $room->id }}"
                    data-title="{{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }} ({{ $room->hotel->title }})"
                    onclick="event.preventDefault(); event.stopPropagation();">
                    <svg style="height: 10px; width: 10px;" class="mr-0.5 flex-shrink-0 sm:mr-1" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="truncate hidden sm:inline">{{ __('reviews.view_reviews') }}</span>
                    <span class="truncate sm:hidden">{{ __('reviews.view') }}</span>
                </button>

                <!-- Кнопка оставить отзыв -->
                <button type="button"
                    class="inline-flex items-center px-1 py-1 bg-green-100 hover:bg-green-200 text-black font-medium text-xs rounded border border-green-300 shadow-sm transition-all duration-200 review-leave-btn flex-shrink-0"
                    style="height: 0.8cm; font-size: 10px; white-space: nowrap;" data-type="room"
                    data-id="{{ $room->id }}"
                    data-title="{{ \App\Helpers\TranslationHelper::translateRoomType($room->type) }} ({{ $room->hotel->title }})"
                    onclick="event.preventDefault(); event.stopPropagation();">
                    <svg style="height: 10px; width: 10px;" class="mr-0.5 flex-shrink-0 sm:mr-1" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="truncate hidden sm:inline">{{ __('reviews.leave_review') }}</span>
                    <span class="truncate sm:hidden">{{ __('reviews.rate') }}</span>
                </button>

                <!-- Кнопка бронирования -->
                @if($isBooked)
                    <div class="inline-flex items-center px-1 bg-gray-200 text-black font-medium text-xs rounded border border-gray-400 shadow-sm cursor-not-allowed opacity-75 flex-shrink-0"
                        style="height: 1cm; font-size: 11px;">
                        <span style="height: 6mm; display: inline-flex; align-items: center;">🚫</span>
                        <span class="ml-1">{{ __('Not available') }}</span>
                    </div>
                @else
                    <a href="{{ route('bookings.create', ['room' => $room->id]) }}"
                        class="inline-flex items-center px-1 bg-purple-100 hover:bg-purple-200 text-black font-medium text-xs rounded border border-purple-300 shadow-sm transition-all duration-200 flex-shrink-0"
                        style="height: 1cm; font-size: 11px;">
                        <span style="height: 6mm; display: inline-flex; align-items: center;">🏨</span>
                        <span class="ml-1">{{ __('Book now') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>