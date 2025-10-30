<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('2fa.confirm_access') }}
    </div>

    <div x-data="{ type: 'app' }">
        <div class="mb-4">
            <div class="flex space-x-4">
                <button @click="type = 'app'" :class="type === 'app' ? 'bg-blue-500 text-white' : 'bg-gray-200'"
                    class="px-4 py-2 rounded">
                    {{ __('2fa.app') }}
                </button>
                <button @click="type = 'email'" :class="type === 'email' ? 'bg-blue-500 text-white' : 'bg-gray-200'"
                    class="px-4 py-2 rounded">
                    {{ __('2fa.email_option') }}
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('two-factor.verify') }}">
            @csrf
            <input type="hidden" name="type" :value="type">

            <div x-show="type === 'app'">
                <x-input-label for="code" :value="__('2fa.app_code')" />
                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" maxlength="6" autofocus />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div x-show="type === 'email'">
                <x-input-label for="email_code" :value="__('2fa.email_code')" />
                <x-text-input id="email_code" class="block mt-1 w-full" type="text" name="code" maxlength="6" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />

                <button type="button" @click="sendEmailCode()" class="mt-2 text-sm text-blue-600 hover:text-blue-500">
                    {{ __('2fa.send_email_code') }}
                </button>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('2fa.confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function sendEmailCode() {
            fetch('{{ route("two-factor.send-email") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("{{ __('2fa.code_sent') }}");
                    }
                });
        }
    </script>
</x-guest-layout>