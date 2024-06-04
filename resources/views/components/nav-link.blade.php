@props(['active'])

@php
$classes = ($active ?? false)
            ? 'kvv-color-text transition duration-150 ease-in-out'
            : 'kvv-color-text-light kvv-hover font-bold transition duration-150 ease-in-out';
$span_classes = ($active ?? false)
            ? 'absolute kvv-background-accent bottom-0 left-0 w-full h-0.5 transition-all duration-300 ease-in-out'
            : 'absolute kvv-background-accent bottom-0 left-0 w-0 group-hover:w-full opacity-0 group-hover:opacity-100 h-0.5 transition-all duration-500 ease-in-out'
@endphp

<a class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 {{ $classes }}" {{ $attributes }}>
    {{ $slot }}
    <span
        class="{{ $span_classes }}" {{ $attributes }}
        style="
            margin-bottom: -5px";
    ></span>
</a>
