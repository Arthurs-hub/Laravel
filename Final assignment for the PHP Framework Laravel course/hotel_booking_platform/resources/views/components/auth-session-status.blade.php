@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-green-100 border-2 border-green-300 text-green-800 px-6 py-3 rounded-xl shadow-lg mb-4 text-center']) }}>
        <div class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ $status }}</span>
        </div>
    </div>
@endif