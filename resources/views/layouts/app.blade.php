<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'sample description' }}">
    <title>KVV Rauw - {{ $title ?? 'sample title' }}</title>
    <x-layout.favicons />
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="flex flex-col space-y-4 min-h-screen text-gray-800 bg-gray-100">
    <header class="shadow sticky inset-0 backdrop-blur-sm z-10">
        {{--  Navigation  --}}
        @livewire('layout.nav-bar')
    </header>
    <main class="relative flex-grow">
        <div class="container mx-auto p-4 flex-1 px-4" style="max-width: 90%">
            {{-- Title --}}
            @empty($hideSubtitle)
                <h1 class="kvv-color-text text-3xl mb-8">
                    {{ $subtitle ?? $title ?? "" }}
                </h1>
            @endempty
            {{-- Main content --}}
            {{ $slot }}
        </div>
    </main>
    <x-layout.footer developer-name="{{$developer ?? 'Jorrit Leysen'}}"/>
</div>
@stack('script')
@livewireScripts
</body>
</html>
