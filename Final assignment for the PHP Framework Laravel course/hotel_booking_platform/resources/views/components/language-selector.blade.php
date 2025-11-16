<div class="relative inline-block text-left" style="z-index: 999999 !important;">
    <div>
        <button type="button"
            class="language-button inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            id="language-menu" aria-expanded="true" aria-haspopup="true">
            @php
                $currentLang = app()->getLocale();
                $langNames = [
                    'en' => '🇺🇸 ' . __('languages.english'),
                    'ru' => '🇷🇺 ' . __('languages.russian'),
                    'fr' => '🇫🇷 ' . __('languages.french'),
                    'de' => '🇩🇪 ' . __('languages.german'),
                    'it' => '🇮🇹 ' . __('languages.italian'),
                    'es' => '🇪🇸 ' . __('languages.spanish'),
                    'ar' => '🇸🇦 ' . __('languages.arabic')
                ];
            @endphp
            {{ $langNames[$currentLang] ?? '🌐 ' . __('nav.language') }}
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="origin-top-right absolute right-0 mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden language-dropdown"
        style="z-index: 999999 !important;" role="menu" aria-orientation="vertical" aria-labelledby="language-menu"
        id="language-dropdown">

        <div class="py-1" role="none">
                @php
                    $currentParams = request()->query();
                @endphp
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'en'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇺🇸 {{ __('languages.english') }}
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'ru'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇷🇺 {{ __('languages.russian') }}
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'fr'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇫🇷 {{ __('languages.french') }}
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'de'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇩🇪 {{ __('languages.german') }}
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'it'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇮🇹 {{ __('languages.italian') }}
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'es'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇪🇸 {{ __('languages.spanish') }}
                </a>
                <a href="{{ request()->url() }}?{{ http_build_query(array_merge($currentParams, ['lang' => 'ar'])) }}"
                    class="block w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                    role="menuitem">
                    🇸🇦 {{ __('languages.arabic') }}
                </a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Находим все языковые селекторы на странице
        const languageContainers = document.querySelectorAll('.relative.inline-block.text-left');

        languageContainers.forEach(function (container, index) {
            const menu = container.querySelector('button[id="language-menu"]');
            const dropdown = container.querySelector('div[id="language-dropdown"]');

            if (menu && dropdown) {
                // Создаем уникальные ID
                const uniqueMenuId = 'language-menu-' + index;
                const uniqueDropdownId = 'language-dropdown-' + index;

                menu.id = uniqueMenuId;
                dropdown.id = uniqueDropdownId;

                // Добавляем обработчик клика
                menu.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Закрываем все другие выпадающие меню
                    languageContainers.forEach(function (otherContainer, otherIndex) {
                        if (otherIndex !== index) {
                            const otherDropdown = otherContainer.querySelector('div[id^="language-dropdown"]');
                            if (otherDropdown) {
                                otherDropdown.classList.add('hidden');
                            }
                        }
                    });

                    // Переключаем текущее меню
                    dropdown.classList.toggle('hidden');
                });
            }
        });

        // Закрытие при клике вне меню
        document.addEventListener('click', function (event) {
            languageContainers.forEach(function (container) {
                const menu = container.querySelector('button[id^="language-menu"]');
                const dropdown = container.querySelector('div[id^="language-dropdown"]');

                if (menu && dropdown && !container.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    });
</script>