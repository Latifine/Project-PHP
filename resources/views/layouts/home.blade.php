<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welkom bij KVV Rauw">
    <title>KVV Rauw</title>
    <x-layout.favicons />
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="flex flex-col min-h-screen text-gray-800 bg-gray-100">
    <header class="shadow sticky inset-0 backdrop-blur-sm z-10">
        {{--  Navigation  --}}
        @livewire('layout.nav-bar')
    </header>
    <main class="flex-grow">
        <div class="h-80 w-full relative">
            <div class="h-80">
                <img class="brightness-75 h-full w-full object-cover" src="/images/hero.jpg" alt="">
            </div>
            <h1 class="absolute text-white text-7xl top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">KVV Rauw</h1>
        </div>
        {{-- Main content --}}
        <div class="container mx-auto p-4 flex-1 px-4">
            {{ $slot }}
        </div>
    </main>
    <x-layout.footer developer-name="Jorrit Leysen"/>
</div>
@stack('script')
@livewireScripts
</body>
</html>
