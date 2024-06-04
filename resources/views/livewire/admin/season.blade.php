<div>
    {{--Filters--}}
    <x-tmk.section class="mb-4 flex gap-2">
        <x-button wire:click="newSeason()" color-on="text-white bg-lime-600"
                  class="w-44 flex justify-center items-center">
            Nieuw seizoen
        </x-button>
    </x-tmk.section>

    @if ($form->errorMessage)
        <x-tmk.section class="mb-4 flex gap-2">
            <x-tmk.list>
                <div class="error-message">
                    @foreach ($form->errorMessage as $error)
                        <li class="text-red-500">{{ $error }}</li>
                    @endforeach
                </div>
            </x-tmk.list>
        </x-tmk.section>
    @endif




    {{-- Table --}}
    <x-tmk.section>
    <table class="text-center w-full border border-gray-300">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th scope="col">Seizoen</th>
            <th scope="col">Begin datum</th>
            <th scope="col">Eind datum</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($seasons as $season)
            <tr class="border-t border-gray-300 h-16" wire:key="season_{{ $season->id }}">
                <td>
                    {{ \Carbon\Carbon::parse($season->date_start)->format('Y') }} -
                    {{ \Carbon\Carbon::parse($season->date_end)->format('Y') }}
                </td>
                <td>{{ $season->date_start }}</td>
                <td>{{ $season->date_end }}</td>

                <td>
                    <button
                            @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u dit seizoen wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder seizoen',
                                        next: {
                                            event: 'delete-season',
                                            params: {
                                                id: {{ $season->id }}
                                            }
                                        }
                                    })"
{{--                        wire:click="deleteSeason({{ $season->id }})"--}}
                        class="text-gray-400 hover:text-red-500 transition">
                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                    <div class="font-bold italic text-sky-800">Geen seizoenen gevonden</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </x-tmk.section>


    {{-- Modal for adding season --}}
    <x-dialog-modal id="seasonModal" wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($form->id) ? 'Nieuwe season aanmaken' : '' }}</h2>
        </x-slot>
        <x-slot name="content">
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif

            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <!-- Conditional rendering for error message -->
                    @if ($form->errorMessage)
                    <x-tmk.alert type="danger">
                            <x-tmk.list>
                                <div class="error-message">
                                    @foreach ($form->errorMessage as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </div>
                            </x-tmk.list>
                    </x-tmk.alert>
                    @endif

                    <x-label for="date_start" value="Start datum nieuw seizoen" class="mt-4"/>
                    <x-input id="date_start" type="date" placeholder="start datum" wire:model="form.date_start"></x-input>
                    <x-label for="date_end" value="Eind datum nieuw seizoen" class="mt-4"/>
                    <x-input id="date_end" type="date" placeholder="Eind datum" wire:model="form.date_end"></x-input>
                    <x-label for="Spelerregistratie" value="Automatische registratie lidgeld" class="mt-4"/>
                    <input for="Spelerregistratie" type="checkbox" id="spelerregistratie" wire:click="updateSpeleRegistratie">
                </div>
            </div>
        </x-slot>


        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            <x-button
                wire:click="createSeason()"
                class="ml-2">Opslaan
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
