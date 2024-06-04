<div>
    {{--Filters--}}
    <x-tmk.section class="mb-4 flex gap-2">
        <div class="flex-1 items-center">
            <x-input id="search" type="text" placeholder="Naam speler"
                     wire:model.live.debounce.500ms="search"
                     class="w-full h-12 shadow-md placeholder-gray-300"/>
        </div>

        <x-tmk.form.switch id="switchPaid"
                           wire:model.live="switchPaid"
                           text-off="Niet betaald"
                           color-off="bg-gray-100 before:line-through"
                           text-on="Betaald"
                           color-on="text-white bg-green-600"
                           class="w-44"
        />

        <x-button wire:click="newRegistration()" color-on="text-white bg-lime-600"
                  class="w-44 flex justify-center items-center">
            Registratie lidgeld toevoegen
        </x-button>
    </x-tmk.section>


    {{-- Table --}}
    <x-tmk.section>
    <table class="text-center w-full border border-gray-300">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th scope="col">Naam speler</th>
            <th scope="col">Seizoen</th>
            <th scope="col">Betaald</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($registrationUsersFilter as $registration)
            <tr class="border-t border-gray-300 h-16" wire:key="registration_{{ $registration->registration_id }}">
                <td>{{ $registration->first_name . ' ' . $registration->name}}</td>
                <td>
                    {{ \Carbon\Carbon::parse($registration->date_start)->format('Y') . '-' . \Carbon\Carbon::parse($registration->date_end)->format('Y') }}
                </td>
                <td>

                    <input wire:click="setRegistrationPaid({{ $registration->registration_id }})" type="checkbox" name="paid_checkbox" {{ $registration->paid ? 'checked' : '' }}>
                </td>
                <td>
{{--                    <x-button
                        wire:click="deleteRegistration({{ $registration->id }})"
                        class="my-4 py-3 text-gray-400 hover:text-red-100 hover:bg-red-500 transition">
                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                    </x-button>--}}
                    <button
                            @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u deze registratie wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder aanvraag',
                                        next: {
                                            event: 'delete-registarion',
                                            params: {
                                                id: {{ $registration->registration_id }}
                                            }
                                        }
                                    })"
{{--                        wire:click="deleteRegistration({{ $registration->id }})"
                        wire:confirm="Bent u zeker dat u deze registratie wilt verwijderen?"--}}
                        class="text-gray-400 hover:text-red-500 transition">
                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                    <div class="font-bold italic text-sky-800">Geen spelers gevonden</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </x-tmk.section>

    {{-- Modal for adding registration --}}
    <x-dialog-modal id="registrationModal" wire:model="showModal">
        <x-slot name="title">
            <h2>{{ is_null($form->id) ? 'Nieuwe registratie' : '' }}</h2>
        </x-slot>
        <x-slot name="content">

            {{-- error messages --}}
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif

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

            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col gap-2">
                    <x-label for="season" value="Season" class="mt-4"/>
                    <x-tmk.form.select wire:model="form.season_id" id="season_id" class="block mt-1 w-full">
                        <option value="">Selecteer een seizoen</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">
                                {{ \Carbon\Carbon::parse($season->date_start)->format('Y') . '-' . \Carbon\Carbon::parse($season->date_end)->format('Y') }}
                            </option>
                        @endforeach
                    </x-tmk.form.select>

                    <x-label for="user" value="Gebruiker" class="mt-4"/>
                    <x-tmk.form.select wire:model="form.user_id" id="user_id" class="block mt-1 w-full">
                        <option value="">Selecteer een speler</option>
                        @foreach($registrationUsers as $user)
                            <option value="{{ $user->user_id }}">{{ $user->first_name . ' ' . $user->name}}</option>
                        @endforeach
                    </x-tmk.form.select>

                    <x-label for="paid" value="Betaald" class="mt-4"/>
                    <input for="paid" type="checkbox" id="paid" wire:model="form.paid">
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="show = false">Annuleer</x-secondary-button>
            <x-button
                wire:click="createRegistration()"
                class="ml-2">Opslaan
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
