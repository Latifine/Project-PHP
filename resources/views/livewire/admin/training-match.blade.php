@php use Carbon\Carbon; @endphp
<div>
    <header>
        <p>Hieronder kan je een overzicht zien van alle matches en trainingen van KVV Rauw.</p>
    </header>
{{--    <x-tmk.form.switch id="upcoming"--}}
{{--                       wire:model="upcoming"--}}
{{--                       text-off="Aanstaande"--}}
{{--                       color-off="bg-gray-100 before:line-through"--}}
{{--                       text-on="Aanstaande"--}}
{{--                       color-on="text-white bg-lime-600"--}}
{{--                       class="w-44 h-11 mt-3"/>--}}
    <h1 class="text-xl mt-5 mb-1">Wedstrijden</h1>
    <x-tmk.section class="mb-4 flex justify-between">
        <x-button class="py-3" wire:click="newMatch()">
            nieuwe wedstrijd
        </x-button>
        <x-tmk.form.switch id="upcoming"
                           wire:model.live="upcoming"
                           text-off="Komende"
                           color-off="bg-gray-100"
                           text-on="Alle"
                           color-on="text-white bg-green-600"
                           class="w-44"
        />
    </x-tmk.section>
    <x-tmk.section>
    <table class="text-center w-full border border-gray-300 mt-3">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th wire:click="resort('date')" scope="col">Datum</th>
            <th wire:click="resort('time')" scope="col">Tijd</th>
            <th wire:click="resort('address')" scope="col">Adres</th>
            <th wire:click="resort('home')" scope="col">Thuismatch</th>
            <th wire:click="resort('opponent')" scope="col">Tegenstander</th>
            <th scope="col">Voorbereiding</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($matchTrainings->where('is_match', true) as $matchTraining)
            <tr wire:key="{{ $matchTraining->id }}" class="border-t border-gray-300 h-16">
                <td>{{ \Carbon\Carbon::parse($matchTraining->date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($matchTraining->time)->format('H:i')}}</td>
                <td>{{ $matchTraining->address }}</td>
                <td><input type="checkbox" name="home_checkbox" {{ $matchTraining->home ? 'checked' : '' }} disabled></td>
                <td>{{ $matchTraining->opponent }}</td>
                <td>{{ $matchTraining->preparation }}</td>
                <td>
                    @if($matchTraining->date > Carbon::now())
                    <div class="border border-gray-300 rounded-md overflow-hidden m-2 grid grid-cols-2 h-10">
                    <button
                        wire:click="editMatch({{ $matchTraining->id }})"
                        class="text-gray-400 hover:text-sky-100 hover:bg-sky-500 transition border-r border-gray-300">
                        <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                    </button>
                    <button
                            @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u deze wedstrijd wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder wedstrijd',
                                        next: {
                                            event: 'delete-matchTraining',
                                            params: {
                                                id: {{ $matchTraining->id }}
                                            }
                                        }
                                    })"
                        class="text-gray-400 hover:text-red-100 hover:bg-red-500 transition">
                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                    </button>
                    </div>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                    <div class="font-bold italic text-sky-800">Geen wedstrijden gevonden</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
        </x-tmk.section>


    <h1 class="text-xl mt-5 mb-1">Trainingen</h1>
    <x-tmk.section class="mb-4 flex justify-between">
        <div class="flex gap-2">
        <x-button class="py-3" wire:click="newTraining()">
        nieuwe training
        </x-button>
        <x-button class="py-3" wire:click="allTrainings()">
            Alle trainingen maken
        </x-button>
        </div>
        <x-tmk.form.switch id="upcoming"
                           wire:model.live="upcoming"
                           text-off="Komende"
                           color-off="bg-gray-100"
                           text-on="Alle"
                           color-on="text-white bg-green-600"
                           class="w-44"
        />
    </x-tmk.section>
    <x-tmk.section>
    <table class="text-center w-full border border-gray-300 mt-3">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th wire:click="resort('date')" scope="col">Datum</th>
            <th wire:click="resort('time')" scope="col">Tijd</th>
            <th scope="col">Voorbereiding</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($matchTrainings->where('is_match', false) as $matchTraining)
            <tr wire:key="{{ $matchTraining->id }}" class="border-t border-gray-300 h-16">
                <td>{{ \Carbon\Carbon::parse($matchTraining->date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($matchTraining->time)->format('H:i') }}</td>
                <td>{{ $matchTraining->preparation }}</td>
                <td>
                    @if($matchTraining->date > Carbon::now())
                    <div class="border border-gray-300 rounded-md overflow-hidden m-2 grid grid-cols-2 h-10">
                        <button
                            wire:click="editTraining({{ $matchTraining->id }})"
                            class="text-gray-400 hover:text-sky-100 hover:bg-sky-500 transition border-r border-gray-300">
                            <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                        </button>
                        <button
                                @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u deze training wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder training',
                                        next: {
                                            event: 'delete-matchTraining',
                                            params: {
                                                id: {{ $matchTraining->id }}
                                            }
                                        }
                                    })"
                            class="text-gray-400 hover:text-red-100 hover:bg-red-500 transition">
                            <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                        </button>
                    </div>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                    <div class="font-bold italic text-sky-800">Geen trainingen gevonden</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </x-tmk.section>

    <x-dialog-modal id="matchModal"
                    wire:model.live="showMatchModal">
        <x-slot name="title">
            <h2>{{ is_null($form->id) ? 'Nieuwe wedstrijd' : 'Wedstrijd bijwerken' }}</h2>
        </x-slot>
        <x-slot name="content">
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="date" value="Datum" class="mt-4"/>
                    <x-input id="title" type="date"
                             wire:model="form.date"
                             class="mt-1 block w-full"/>
                    <x-label for="time" value="Tijd" class="mt-4"/>
                    <x-input id="title" type="time"
                             wire:model="form.time"
                             class="mt-1 block w-full"/>
                    <x-label for="address" value="Adres" class="mt-4"/>
                    <x-input id="address" type="text"
                             wire:model="form.address"
                             class="mt-1 block w-full"/>
                    <x-label for="home" value="Thuismatch" class="mt-4"/>
                    <input type="checkbox" id="{{ rand() }}" {{ !!$form->home ? 'checked' : '' }} wire:model="form.home">
                    <x-label for="opponent" value="Tegenstander" class="mt-4"/>
                    <x-input id="opponent" type="text"
                             wire:model="form.opponent"
                             class="mt-1 block w-full"/>
                    <x-label for="preparation" value="Voorbereiding" class="mt-4"/>
                    <x-input id="preparation" type="text"
                             wire:model="form.preparation"
                             class="mt-1 block w-full"/>
                    @if ($errors->any())
                        <x-tmk.alert type="danger">
                            <x-tmk.list>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </x-tmk.list>
                        </x-tmk.alert>
                    @endif
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="$wire.showMatchModal = false">Annuleer</x-secondary-button>
            @if(is_null($form->id))
                <x-button
                    wire:click="createMatch()"
                    wire:loading.attr="disabled"
                    class="ml-2">Nieuwe wedstrijd opslaan
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateMatch({{ $form->id }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Wedstrijd bijwerken
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="trainingModal"
                    wire:model.live="showTrainingModal">
        <x-slot name="title">
            <h2>{{ is_null($form->id) ? 'Nieuwe training' : 'Training bijwerken' }}</h2>
        </x-slot>
        <x-slot name="content">
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="date" value="Datum" class="mt-4"/>
                    <x-input id="title" type="date"
                             wire:model="form.date"
                             class="mt-1 block w-full"/>
                    <x-label for="time" value="Tijd" class="mt-4"/>
                    <x-input id="title" type="time"
                             wire:model="form.time"
                             class="mt-1 block w-full"/>
                    <x-label for="preparation" value="Voorbereiding" class="mt-4"/>
                    <x-input id="preparation" type="text"
                             wire:model="form.preparation"
                             class="mt-1 block w-full"/>
                    @if ($errors->any())
                        <x-tmk.alert type="danger">
                            <x-tmk.list>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </x-tmk.list>
                        </x-tmk.alert>
                    @endif
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="$wire.showTrainingModal = false">Annuleer</x-secondary-button>
            @if(is_null($form->id))
                <x-button
                    wire:click="createTraining()"
                    wire:loading.attr="disabled"
                    class="ml-2">Nieuwe training opslaan
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateTraining({{ $form->id }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Training bijwerken
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
    <x-dialog-modal id="allTrainingModal"
                    wire:model.live="showAllTrainingModal">
        <x-slot name="title">
            <h2>{{ 'Alle trainingen aanmaken' }}</h2>
        </x-slot>
        <x-slot name="content">
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="endDate" value="Tot en met:" class="mt-4"/>
                    <x-input id="endDate" type="date"
                             wire:model="form.endDate"
                             class="mt-1 block w-full"/>
                    <x-label for="day" value="Dag" class="mt-4"/>
                    <x-tmk.form.select id="day" wire:model.live="form.day" class="block mt-1 w-full">
                        <option value="Monday">Maandag</option>
                        <option value="Tuesday">Dinsdag</option>
                        <option value="Wednesday">Woensdag</option>
                        <option value="Thursday">Donderdag</option>
                        <option value="Friday">Vrijdag</option>
                    </x-tmk.form.select>
                        <x-label for="trainingTime" value="Tijd" class="mt-4"/>
                    <x-input id="trainingTime" type="time"
                             wire:model="form.trainingTime"
                             class="mt-1 block w-full"/>
                    @if ($errors->any())
                        <x-tmk.alert type="danger">
                            <x-tmk.list>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </x-tmk.list>
                        </x-tmk.alert>
                    @endif
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="$wire.showAllTrainingModal = false">Annuleer</x-secondary-button>
                <x-button
                    wire:click="createAllTrainings()"
                    wire:loading.attr="disabled"
                    class="ml-2">Trainingen aanmaken
                </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
