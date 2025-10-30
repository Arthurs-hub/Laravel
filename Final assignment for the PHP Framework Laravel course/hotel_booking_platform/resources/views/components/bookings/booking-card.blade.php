<div {{ $attributes->merge(['class' => 'flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8']) }}>
    <div class="flex flex-col justify-start items-start bg-gray-50 px-4 py-4 md:px-6 xl:px-8 w-full">
        <div class="flex justify-between w-full py-2 border-b border-gray-200">
            <div class="w-full">
                <p class="text-lg md:text-xl font-semibold leading-6 xl:leading-5 text-gray-800">
                    {{ __('booking.booking') }}
                    #{{ $booking->id }}
                </p>
                <p class="text-base font-medium leading-6 text-gray-600 ">
                    {{ \App\Helpers\TranslationHelper::formatDateTime($booking->created_at, 'd-m-y H:i') }}
                </p>
            </div>
            @if($showLink ?? false)
                <div class="flex">
                    <x-link-button
                        href="{{ route('bookings.show', ['booking' => $booking]) }}">{{ __('booking.details') }}</x-link-button>
                </div>
            @endif
        </div>
        <div class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:space-x-6 w-full">
            <div class="pb-4 w-full md:w-2/5">
                <img class="w-full block" src="{{ $booking->room->poster_url }}" alt="image" />
            </div>
            <div
                class="md:flex-row flex-col flex justify-between items-start w-full md:w-3/5 pb-8 space-y-4 md:space-y-0">
                <div class="w-full flex flex-col justify-start items-start space-y-8">
                    <h3 class="text-xl xl:text-2xl font-semibold leading-6 text-gray-800">
                        {{ \App\Helpers\TranslationHelper::translateRoomType($booking->room->type) }}
                    </h3>
                    <div class="flex justify-start items-start flex-col space-y-2">
                        <p class="text-sm leading-none text-gray-800"><span>{{ __('booking.dates') }}: </span>
                            {{ \App\Helpers\TranslationHelper::formatDate($booking->started_at) }}
                            {{ __('booking.to') }}
                            {{ \App\Helpers\TranslationHelper::formatDate($booking->finished_at) }}
                        </p>
                        <p class="text-sm leading-none text-gray-800"><span>{{ __('booking.number_of_nights') }}:
                            </span> {{ $booking->days }}
                        </p>
                        <p class="text-sm leading-none text-gray-800"><span>{{ __('booking.price') }}: </span>
                            {{ $booking->formatted_price }}</p>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 items-end w-full">
                    @if($booking->canReview())
                        <a href="{{ route('reviews.create', $booking) }}"
                            class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700">
                            ✍️ {{ __('booking.review') }}
                        </a>
                    @endif
                    <a href="{{ route('bookings.show', $booking) }}"
                        class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700">
                        {{ __('booking.details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>