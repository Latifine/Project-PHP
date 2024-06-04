<div>
    <x-kvv.h1>Komende wedstrijden</x-kvv.h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse ($matches as $match)
            <div wire:key="{{ $match->id }}" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow text-center mx-auto">
                <p class="mb-5 text-2xl kvv-color-text">{{ \Carbon\Carbon::parse($match->time)->format('H:i') }} - {{ \Carbon\Carbon::parse($match->date)->format('d-m-Y') }}</p>
                <div class="grid grid-cols-7 mx-auto kvv-color-text-light font-normal">

                    @php
                        $team1 = $match->home ? 'KVV Rauw U10' : $match->opponent;
                        $team2 = $match->home ? $match->opponent : 'KVV Rauw U10';
                        $home_team_icon = '<img class="h-7" src="/assets/icons/android-chrome-256x256.png" alt="">';
                    @endphp

                    <div class="flex items-center col-span-3 gap-2">
                        @if ($match->home)
                            {!! $home_team_icon !!}
                        @endif
                        <p>{{ $team1 }}</p>
                    </div>
                    <p class="flex items-center justify-center">-</p>
                    <div class="flex items-center col-span-3 gap-x-2">
                        @if (!$match->home)
                            {!! $home_team_icon !!}
                        @endif
                        <p>{{ $team2 }}</p>
                    </div>

                </div>
            </div>
            @empty
                <p class="mt-4 text-center font-bold italic kvv-color-text-light col-span-3">Geen opkomende wedstrijden</p>
            @endforelse
        </div>
</div>
