<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('2fa.title') }} - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">🏨 {{ config('app.name') }}</h1>
                @if(auth()->user()->two_factor_method === 'email')
                    <p class="text-gray-600 mt-2">{{ __('2fa.email_sent') }}</p>
                    @if(config('mail.default') === 'log')
                        <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg text-sm">
                            <strong>{{ __('Attention') }}:</strong> {{ __('2fa.email_sent_log') }}
                        </div>
                    @endif
                @else
                    <p class="text-gray-600 mt-2">{{ __('2fa.scan_qr') }}</p>
                @endif
            </div>

            @if(auth()->user()->two_factor_method === 'google_authenticator' && isset($qrCode))
                <div class="mb-8 text-center">
                    <div class="inline-block p-4 bg-gray-50 rounded-xl">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCode) }}"
                            alt="QR Code" class="mx-auto rounded">
                    </div>
                    <p class="text-sm text-gray-600 mt-3">{{ __('2fa.scan_qr_instruction') }}</p>

                    <div class="mt-6 p-4 bg-gray-50 rounded-xl text-left">
                        <h4 class="font-semibold mb-3 text-center">{{ __('2fa.manual_entry') }}</h4>
                        <div class="space-y-2 text-sm">
                            <div><strong>{{ __('2fa.account_name') }}:</strong> {{ auth()->user()->email }}</div>
                            <div><strong>{{ __('2fa.account_key') }}:</strong> <code
                                    class="bg-white px-2 py-1 rounded border text-xs">{{ auth()->user()->two_factor_secret }}</code>
                            </div>
                            <div><strong>{{ __('2fa.issuer_name') }}:</strong> {{ config('app.name') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="code"
                        class="block text-sm font-semibold text-gray-700 mb-2">{{ __('2fa.confirmation_code') }}</label>
                    <input id="code" type="text" name="code" required autofocus maxlength="6" placeholder="000000"
                        class="w-full px-4 py-3 text-center text-2xl tracking-widest border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        oninput="handleCodeInput(this)">
                    @if($errors->has('code'))
                        <div class="text-red-600 text-sm mt-2">{{ $errors->first('code') }}</div>
                    @endif
                    <div id="loading-message" class="hidden text-center text-blue-600 text-sm mt-2">
                        🔄 {{ __('2fa.verifying_code') }}
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    @if(auth()->user()->two_factor_method === 'email')
                        <button type="button" id="resend-code"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            {{ __('2fa.resend_code') }}
                        </button>
                    @else
                        <div></div>
                    @endif

                    <button type="submit" id="submit-btn"
                        class="bg-blue-600 text-white py-3 px-6 rounded-xl font-semibold hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                        {{ __('2fa.confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let isSubmitting = false;

        function handleCodeInput(input) {
            const submitBtn = document.getElementById('submit-btn');
            const loadingMsg = document.getElementById('loading-message');

            if (input.value.length === 6 && !isSubmitting) {
                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.textContent = '{{ __("2fa.verifying_code") }}';
                loadingMsg.classList.remove('hidden');
                input.form.submit();
            }
        }

        document.querySelector('form').addEventListener('submit', function (e) {
            const submitBtn = document.getElementById('submit-btn');
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.textContent = '{{ __("2fa.verifying_code") }}';
        });

        @if(auth()->user()->two_factor_method === 'email')
            document.getElementById('resend-code')?.addEventListener('click', function () {
                this.textContent = '{{ __("2fa.sending") }}';
                this.disabled = true;

                fetch('{{ route("two-factor.resend") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.textContent = '{{ __("2fa.code_sent") }}';
                            setTimeout(() => {
                                this.textContent = '{{ __("2fa.resend_code") }}';
                                this.disabled = false;
                            }, 3000);
                        }
                    })
                    .catch(() => {
                        this.textContent = '{{ __("2fa.error") }}';
                        setTimeout(() => {
                            this.textContent = '{{ __("2fa.resend_code") }}';
                            this.disabled = false;
                        }, 2000);
                    });
            });
        @endif
    </script>
</body>

</html>