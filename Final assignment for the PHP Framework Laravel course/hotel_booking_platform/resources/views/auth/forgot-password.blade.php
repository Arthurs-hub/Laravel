<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Reset Password') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        @if (session('status'))
            <div id="success-message" class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="text-green-600 text-6xl mb-4">✓</div>
                <h1 class="text-2xl font-bold text-gray-900 mb-4">🏨 {{ config('app.name') }}</h1>
                <p class="text-gray-600 mb-6">{{ __('We have emailed your password reset link!') }}</p>
                <div class="text-sm text-gray-500">{{ __('Redirecting to login page in') }} <span id="countdown">3</span> {{ __('seconds') }}...</div>
            </div>
            <script>
                let count = 3;
                const countdown = document.getElementById('countdown');
                const timer = setInterval(() => {
                    count--;
                    countdown.textContent = count;
                    if (count <= 0) {
                        clearInterval(timer);
                        window.location.href = '{{ route("login") }}';
                    }
                }, 1000);
            </script>
        @else
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">🏨 {{ config('app.name') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('Enter your email to reset password') }}</p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Email Address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @if($errors->has('email'))
                            <div class="text-red-600 text-sm mt-2">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        {{ __('Send Password Reset Link') }}
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">{{ __('Back to Login') }}</a>
                    </div>
                </form>
            </div>
        @endif
    </div>
</body>
</html>