@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Hero section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 py-16">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-4xl lg:text-6xl font-bold mb-4 text-black">🏨 {{ __('Hotel Catalog') }}</h1>
                    <p class="text-xl mb-8 text-black">{{ __('Discover the best hotels in the world') }}</p>

                    <!-- Filters - Адаптивные -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-6 max-w-6xl mx-auto">
                        <form id="filter-form" action="{{ route('hotels.index') }}" method="GET">
                            <div class="flex flex-col lg:flex-row gap-6 mb-4">

                                <!-- Основные фильтры - адаптивная сетка -->
                                <div class="flex-1">
                                    <!-- Первая строка: Страна и Рейтинг -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        <!-- Country -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 text-black">🌍
                                                {{ __('Country') }}</label>
                                            <select name="country"
                                                class="w-full px-4 py-3 rounded-xl bg-white text-gray-900 border-0 focus:ring-2 focus:ring-blue-500">
                                                <option value="">{{ __('All countries') }}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country }}" @if($country === $selectedCountry) selected
                                                    @endif>{{ $country }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Rating -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 text-black">⭐
                                                {{ __('Rating') }}</label>
                                            <select name="rating"
                                                class="w-full px-4 py-3 rounded-xl bg-white text-gray-900 border-0 focus:ring-2 focus:ring-blue-500">
                                                <option value="">{{ __('All ratings') }}</option>
                                                <option value="5" @if($selectedRating == 5) selected @endif>⭐⭐⭐⭐⭐ (5)</option>
                                                <option value="4" @if($selectedRating == 4) selected @endif>⭐⭐⭐⭐ (4+)</option>
                                                <option value="3" @if($selectedRating == 3) selected @endif>⭐⭐⭐ (3+)</option>
                                                <option value="2" @if($selectedRating == 2) selected @endif>⭐⭐ (2+)</option>
                                                <option value="1" @if($selectedRating == 1) selected @endif>⭐ (1+)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Вторая строка: Цена от и Цена до -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        <!-- Price from -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 text-black">💰
                                                {{ __('Price from') }}</label>
                                            <input type="number" name="price_from" value="{{ request('price_from') }}"
                                                placeholder="{{ __('from') }} {{ $priceRange['min'] }} {{ \App\Helpers\TranslationHelper::getCurrencySymbol() }}"
                                                class="w-full px-4 py-3 rounded-xl bg-white text-gray-900 border-0 focus:ring-2 focus:ring-blue-500">
                                        </div>

                                        <!-- Price to -->
                                        <div>
                                            <label class="block text-sm font-medium mb-2 text-black">💸
                                                {{ __('Price to') }}</label>
                                            <input type="number" name="price_to" value="{{ request('price_to') }}"
                                                placeholder="{{ __('to') }} {{ $priceRange['max'] }} {{ \App\Helpers\TranslationHelper::getCurrencySymbol() }}"
                                                class="w-full px-4 py-3 rounded-xl bg-white text-gray-900 border-0 focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Facilities - адаптивный блок -->
                                @if($facilities->count() > 0)
                                    <div class="w-full lg:w-auto lg:min-w-[300px] flex flex-col">
                                        <label class="block text-sm font-medium mb-2 text-black">✨
                                            {{ __('Facilities') }}</label>
                                        <div
                                            class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-2 max-w-full lg:max-w-md mx-auto flex-grow">
                                            @foreach($facilities->take(8) as $facility)
                                                <label
                                                    class="flex items-center bg-white/20 rounded-lg p-2 cursor-pointer hover:bg-white/30 transition-colors text-sm">
                                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                                        @if(in_array($facility->id, (array) request('facilities', []))) checked
                                                        @endif
                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2 flex-shrink-0">
                                                    <span
                                                        class="text-black text-xs sm:text-sm">{{ \App\Helpers\TranslationHelper::translateFacility($facility->title) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="flex gap-2 mt-3 justify-center">
                                            <button type="button" onclick="clearFacilities()"
                                                class="px-3 py-2 bg-white/20 hover:bg-white/30 text-black text-xs sm:text-sm rounded-lg transition-colors">
                                                {{ __('Clear') }}
                                            </button>
                                            <button type="submit"
                                                class="px-3 py-2 bg-white/20 hover:bg-white/30 text-black text-xs sm:text-sm rounded-lg transition-colors">
                                                🔍 {{ __('Apply filter') }}
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Search button -->
                            <div class="mt-4">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-black font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                    🔍 {{ __('Search') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="container mx-auto px-4 py-12">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <!-- Информация о результатах -->
                <div class="mb-8">
                    <div>
                        @if($selectedCountry)
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                {{ __('Hotels in country: :country', ['country' => $selectedCountry]) }}
                            </h2>
                            <p class="text-gray-600">
                                {{ __('Found :count hotels', ['count' => is_countable($hotels) ? $hotels->count() : count($hotels)]) }}
                            </p>
                        @else
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Popular hotels by countries') }}</h2>
                            <p class="text-gray-600">{{ __('Explore our curated selection of top-rated hotels') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Sorting on the right -->
                <div class="flex justify-end mb-8">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-700">{{ __('Sorting:') }}</label>
                        <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                            class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" @if(!request('sort')) selected @endif>{{ __('Default') }}</option>
                            <option value="price_asc" @if(request('sort') == 'price_asc') selected @endif>
                                {{ __('Price: ascending') }}
                            </option>
                            <option value="price_desc" @if(request('sort') == 'price_desc') selected @endif>
                                {{ __('Price: descending') }}
                            </option>
                            <option value="rating_desc" @if(request('sort') == 'rating_desc') selected @endif>
                                {{ __('Rating: best first') }}
                            </option>
                            <option value="name_asc" @if(request('sort') == 'name_asc') selected @endif>
                                {{ __('Alphabetically') }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Hotels grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($hotels as $hotel)
                        <x-hotels.hotel-card :hotel="$hotel" />
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-16">
                                <div class="text-6xl mb-4">😢</div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('hotels.no_hotels_found') }}</h3>
                                <p class="text-gray-600 mb-6">{{ __('hotels.try_changing_parameters') }}</p>
                                <div class="space-y-3">
                                    <a href="{{ route('hotels.index') }}"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        {{ __('hotels.show_all') }}
                                    </a>
                                    <button onclick="clearFilters()"
                                        class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors ml-3">
                                        {{ __('hotels.clear_filters') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($hotels instanceof \Illuminate\Pagination\LengthAwarePaginator || $hotels instanceof \Illuminate\Pagination\Paginator)
                    <div class="mt-12 flex justify-center">
                        {{ $hotels->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelector('select[name="country"]').addEventListener('change', function () {
            document.getElementById('filter-form').submit();
        });

        function clearFilters() {
            const form = document.getElementById('filter-form');
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else if (input.name !== 'country') {
                    input.value = '';
                }
            });
            form.submit();
        }

        function clearFacilities() {
            const facilityCheckboxes = document.querySelectorAll('input[name="facilities[]"]');
            facilityCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    </script>

    <!-- Review modals for functionality -->
    <x-review-modals />
@endsection