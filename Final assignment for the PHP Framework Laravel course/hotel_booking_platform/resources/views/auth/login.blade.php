<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('login.title') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Language Selector -->
        <div class="mb-4 text-center">
            <x-language-selector />
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 whitespace-nowrap">🏨 {{ config('app.name') }}</h1>
                @if (session('status'))
                    <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email"
                        class="block text-sm font-semibold text-gray-700 mb-2">{{ __('login.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if($errors->has('email'))
                        <div class="text-red-600 text-sm mt-2">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-semibold text-gray-700 mb-2">{{ __('login.password') }}</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if($errors->has('password'))
                        <div class="text-red-600 text-sm mt-2">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">{{ __('login.remember_me') }}</label>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    {{ __('login.login_button') }}
                </button>

                <div class="flex items-center justify-between text-sm">
                    <a href="{{ route('password.request') }}"
                        class="text-blue-600 hover:text-blue-800">{{ __('login.forgot_password') }}</a>
                    <a href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-800">{{ __('login.register_link') }}</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>