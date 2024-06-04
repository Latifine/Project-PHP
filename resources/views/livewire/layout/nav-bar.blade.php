@php use Illuminate\Support\Facades\Auth; @endphp
<nav class="px-4 py-2 flex justify-between bg-white shadow-md">
    {{-- left navigation--}}
    <div>
        <a href="{{ route('home') }}">
            <img width="56" src="/assets/icons/android-chrome-256x256.png" alt="">
        </a>
    </div>
    <div class="flex items-center justify-center space-x-4">
        <x-nav-link href="{{route('kalender')}}" :active="request()->routeIs('kalender')">
            Kalender
        </x-nav-link>
        <x-nav-link href="{{route('photo')}}" :active="request()->routeIs('photo')">
            Galerij
        </x-nav-link>

        @if(Auth::user() && Auth::user()->is_active)
            <x-nav-link href="{{ route('carpool') }}" :active="request()->routeIs('carpool')">
                Carpool
            </x-nav-link>
            <x-nav-link href="{{route('beurtrol')}}" :active="request()->routeIs('beurtrol')">
                Beurtrol
            </x-nav-link>
            <x-nav-link href="{{ route('attendance') }}" :active="request()->routeIs('attendance')">
                Afwezigheid
            </x-nav-link>
        @endif
    </div>

    {{-- right navigation --}}
    <div class="relative flex items-center space-x-2">
        @guest()
            <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                Login
            </x-nav-link>
            <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                Registreer
            </x-nav-link>
        @endguest
        {{-- dropdown navigation--}}
        @auth
            <x-dropdown align="right" width="48">
                {{-- avatar --}}
                <x-slot name="trigger">
                    <img class="rounded-full h-10 w-10 object-cover cursor-pointer"
                         src="{{ $avatar }}"
                         alt="{{ auth()->user()->first_name }} {{ auth()->user()->name }}">
                </x-slot>
                <x-slot name="content">
                    {{-- all users --}}
                    <div class="block px-4 py-2 text-xs text-gray-400">{{ auth()->user()->first_name }} {{ auth()->user()->name }}</div>
                    <x-dropdown-link href="{{ route('profile.show') }}">Instellingen</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">Logout</button>
                    </form>
                    <div class="border-t border-gray-100"></div>
                    <div class="block px-4 py-2 text-xs text-gray-400">Beheren</div>
                    <x-dropdown-link href="{{ route('kinderen') }}">Mijn Kinderen</x-dropdown-link>
                    @if(Auth::user()->role_id === 1)
                        <x-dropdown-link href="{{ route('admin.training-match') }}">Wedstrijden & Trainingen</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.spelersOverzicht') }}">Spelers</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.users.registration') }}">Registraties</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.clothing') }}">Kledij</x-dropdown-link>
                        <x-dropdown-link href="{{ route('admin.season') }}">Seizoenen</x-dropdown-link>
                    @endif
                </x-slot>
            </x-dropdown>
        @endauth
    </div>
</nav>
