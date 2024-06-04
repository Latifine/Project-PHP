@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}
>{{ $slot }}</textarea>

{{--
    >{{ $slot }}</textarea>
    on one line otherwise the component contains leading spaces as a default value and, if you set a placeholder attribute, it will not be displayed!
--}}
