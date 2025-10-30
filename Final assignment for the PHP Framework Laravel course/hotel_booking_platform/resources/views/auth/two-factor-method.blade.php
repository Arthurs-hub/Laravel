<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('2fa.choose_method') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">🏨 {{ config('app.name') }}</h1>
                <p class="text-gray-600 mt-2">{{ __('2fa.choose_method_desc') }}</p>
            </div>

            <form method="POST" action="{{ route('two-factor.method') }}" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <label
                        class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 transition-colors">
                        <input type="radio" name="method" value="email" class="w-5 h-5 text-blue-600" required>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">{{ __('2fa.email_method') }}</div>
                            <div class="text-sm text-gray-500">{{ __('2fa.email_method_desc') }}</div>
                        </div>
                    </label>

                    <label
                        class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 transition-colors">
                        <input type="radio" name="method" value="google_authenticator" class="w-5 h-5 text-blue-600"
                            required>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">{{ __('2fa.google_auth_method') }}</div>
                            <div class="text-sm text-gray-500">{{ __('2fa.google_auth_method_desc') }}</div>
                        </div>
                    </label>
                </div>

                @if($errors->has('method'))
                    <div class="text-red-600 text-sm">{{ $errors->first('method') }}</div>
                @endif

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                    {{ __('2fa.continue') }}
                </button>
            </form>
        </div>
    </div>
</body>

</html>