<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Двухфакторная аутентификация') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(!$user->two_factor_enabled)
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Настройка двухфакторной аутентификации') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Добавьте дополнительный уровень безопасности к вашему аккаунту.') }}
                                </p>
                            </header>

                            <div class="mt-6">
                                <p class="text-sm text-gray-600 mb-4">
                                    1. Установите приложение Google Authenticator или аналогичное<br>
                                    2. Отсканируйте QR-код ниже<br>
                                    3. Введите 6-значный код из приложения
                                </p>

                                <div class="mb-4">
                                    {!! $qrCodeSvg !!}
                                </div>

                                <form method="post" action="{{ route('profile.two-factor.enable') }}">
                                    @csrf
                                    <div>
                                        <x-input-label for="code" :value="__('Код из приложения')" />
                                        <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" maxlength="6" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('code')" />
                                    </div>

                                    <div class="flex items-center gap-4 mt-4">
                                        <x-primary-button>{{ __('Включить 2FA') }}</x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    @else
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Двухфакторная аутентификация включена') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Ваш аккаунт защищен двухфакторной аутентификацией.') }}
                                </p>
                            </header>

                            @if($user->two_factor_recovery_codes)
                                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <h3 class="text-sm font-medium text-yellow-800">Коды восстановления</h3>
                                    <p class="text-sm text-yellow-700 mt-1">Сохраните эти коды в безопасном месте:</p>
                                    <div class="mt-2 grid grid-cols-2 gap-2">
                                        @foreach($user->two_factor_recovery_codes as $code)
                                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $code }}</code>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <form method="post" action="{{ route('profile.two-factor.disable') }}" class="mt-6">
                                @csrf
                                @method('delete')

                                <div>
                                    <x-input-label for="password" :value="__('Пароль для подтверждения')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>

                                <div class="flex items-center gap-4 mt-4">
                                    <x-danger-button>{{ __('Отключить 2FA') }}</x-danger-button>
                                </div>
                            </form>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>