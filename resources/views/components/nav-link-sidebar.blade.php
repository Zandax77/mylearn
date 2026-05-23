@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-6 py-4 text-xs font-black rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-300 transform scale-[1.02]'
            : 'flex items-center px-6 py-4 text-xs font-bold rounded-2xl text-gray-400 dark:text-gray-500 hover:bg-indigo-50 dark:hover:bg-gray-700/50 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center">
        <div class="w-8 flex justify-center mr-3 shrink-0">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $icon }}"></path>
            </svg>
        </div>
        <span class="uppercase tracking-[0.15em] whitespace-nowrap">{{ $slot }}</span>
    </div>
</a>
