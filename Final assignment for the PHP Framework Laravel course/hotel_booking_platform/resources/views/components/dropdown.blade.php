@props(['align' => 'right', 'width' => 'auto', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700'])

@php
    if ($align === 'left') {
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
    } elseif ($align === 'top') {
        $alignmentClasses = 'origin-top';
    } else {
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
    }

    if ($width === '48') {
        $widthClass = 'w-48';
    } elseif ($width === 'auto' || $width === 'max') {
        // use inline-block + shrink helper so dropdown fits content
        $widthClass = 'inline-block dropdown-shrink';
    } elseif ($width === 'user') {
        // fixed width for user-menu (handled in CSS)
        $widthClass = 'user-width';
    } elseif (trim($width) === '') {
        $widthClass = '';
    } else {
        $widthClass = $width;
    }
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="absolute mt-2 {{ $alignmentClasses }}"
        style="z-index:999999; display:none;" @click="open = false">
        <div class="{{ $widthClass }} rounded-md shadow-lg">
            <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }} whitespace-nowrap">
                {{ $content }}
            </div>
        </div>
    </div>
</div>