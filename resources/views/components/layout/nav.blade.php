<nav class="container mx-auto p-4 flex justify-between">
    {{-- left navigation--}}
    <div class="flex items-center space-x-2">
        <a class="hidden sm:block font-medium text-lg" href="{{ route('home') }}">
            Home
        </a>
        <x-nav-link href="{{ route('training-match') }}" :active="request()->routeIs('matchTraining')">
            Test
        </x-nav-link>
        <x-nav-link href="{{ route('admin.clothing') }}" :active="request()->routeIs('admin.clothing')">
            Clothing
        </x-nav-link>
        <x-nav-link href="{{ route('create-carpool') }}" :active="request()->routeIs('create-carpool')">
            Creëer carpool
        </x-nav-link>
    </div>

    {{-- right navigation --}}
    <div class="relative flex items-center space-x-2">

    </div>
</nav>
