<div>

    <x-tmk.section class="mb-4 flex gap-2">
{{--        <div class="flex-1">--}}
{{--            <x-input id="search" type="text" placeholder="Filter Artist Or Record"--}}
{{--                     class="w-full shadow-md placeholder-gray-300"/>--}}
{{--        </div>--}}
{{--        <x-tmk.form.switch id="noStock"--}}
{{--                           text-off="No stock"--}}
{{--                           color-off="bg-gray-100 before:line-through"--}}
{{--                           text-on="No stock"--}}
{{--                           color-on="text-white bg-lime-600"--}}
{{--                           class="w-20 h-auto" />--}}
{{--        <x-tmk.form.switch id="noCover"--}}
{{--                           text-off="Records without cover"--}}
{{--                           color-off="bg-gray-100 before:line-through"--}}
{{--                           text-on="Records without cover"--}}
{{--                           color-on="text-white bg-lime-600"--}}
{{--                           class="w-44 h-auto" />--}}
        <x-button wire:click="newCarpool()">
            + carpool aanbieden
        </x-button>
    </x-tmk.section>

    {{-- Table with records --}}
    <x-tmk.section>
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="">
                <col class="">
                <col class="">
                <col class="">
                <col class="">
                <col class="">
                <col class="">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th>Naam</th>
                <th>Aantal vrije plaatsen</th>
                <th>Locatie</th>
                <th>Datum</th>
                <th class="text-left">Uur</th>
                <th>Reserveer een plaats</th>
                <th>Bewerk de carpool</th>
{{--                <th>--}}
{{--                    <x-tmk.form.select id="perPage"--}}
{{--                                       class="block mt-1 w-full">--}}
{{--                        <option value="5">5</option>--}}
{{--                        <option value="10">10</option>--}}
{{--                        <option value="15">15</option>--}}
{{--                        <option value="20">20</option>--}}
{{--                    </x-tmk.form.select>--}}
{{--                </th>--}}
            </tr>
            </thead>
            <tbody>
            @if($carpools->isEmpty())
                <tr>
                    <td colspan="7" class="border-t border-gray-300 p-4 text-center text-gray-500">
                        <div class="font-bold italic text-sky-800">Geen carpools gevonden</div>
                    </td>
                </tr>
            @else
                @foreach($carpools as $carpool)
                    <?php
                        $user = \Illuminate\Support\Facades\DB::table('users')
                        ->where('users.id', '=', $carpool->user_id)
                        ->first();
                        foreach ($training as $train)
                            if ($train->id == $carpool->training_matches_id)
                                $vartraining = $train
                        ?>
                <tr wire:key="{{$carpool->id}}" class="border-t border-gray-300 h-16">
                    <td>{{$user->first_name}} {{$user->name}}</td>
                    <td>
                        {{$carpool->quantity}}
                    </td>
                    <td>{{$carpool->address}}</td>
                    <td>{{$train->is_match ? "Wedstrijd" : "Training"}} {{\Carbon\Carbon::parse($vartraining->date)->format('d-m-Y')}} {{\Carbon\Carbon::parse($vartraining->date)->format('H:i')}}</td>
                    <td class="text-left">{{\Carbon\Carbon::parse($carpool->hour)->format('H:i')}}</td>
                    <td>
                        @if($user->id != $currentUser->id )
                            @php
                                $alreadyParticipant = false;
                                foreach($carpoolpeople as $carpoolperson) {
                                    if($carpool->id == $carpoolperson->carpool_id && $currentUser->id == $carpoolperson->user_id) {
                                        $alreadyParticipant = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if(!$alreadyParticipant && $carpool->quantity > 0)

                                <button
                                    wire:click="newCarpoolPerson({{ $carpool }})"
                                    class="text-gray-400 hover:text-green-500 transition">
                                    <x-phosphor-check-square-offset-duotone class="inline-block w-5 h-5"/>
                                </button>

                            @endif
                        @endif

                        @foreach($carpoolpeople as $carpoolperson)
                            @if($carpool->id == $carpoolperson->carpool_id)
                                @if($currentUser->id == $carpoolperson->user_id)
                                        <button

                                            data-tippy-content="Kies hoeveel plaatsen"
                                            wire:click="editCarpoolPerson({{ $carpoolperson }})"
                                            class="text-gray-400 hover:text-sky-600 transition">
                                            <x-phosphor-pencil-circle-duotone class="inline-block w-5 h-5"/>
                                        </button>

                                        <button
                                            data-tippy-content="Reservatie verwijderen"
                                            @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u de plaatsen verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder plaatsen',
                                        next: {
                                            event: 'delete-plaatsen',
                                            params: {
                                                id: {{ $carpoolperson->id }}
                                            }
                                        }
                                    })"
                                            {{--                                    wire:click="deleteCarpoolPerson({{ $carpoolperson }})"--}}
                                            {{--                                    wire:confirm="ben je zeker dat je je plaatsen wilt verwijderen?"--}}
                                            class="text-gray-400 hover:text-red-500 transition">
                                            <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                                        </button>
                                @endif
                            @endif
                        @endforeach
                    </td>

                    <td>
                        @if($user->id == $currentUser->id)
                        <div class="border border-gray-300 rounded-md overflow-hidden m-2 grid grid-cols-2 h-10">
                            <button
                                wire:click="editCarpool({{$carpool}})"
                                class="text-gray-400 hover:text-sky-100 hover:bg-sky-500 transition border-r border-gray-300">
                                <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                            </button>
                            <button
                                    @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat je deze carpool wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder carpool',
                                        next: {
                                            event: 'delete-carpool',
                                            params: {
                                                id: {{ $carpool->id }}
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
                @endforeach
            @endif
            </tbody>
        </table>
    </x-tmk.section>

    <x-dialog-modal id="CarpoolPersonModal"
                    wire:model.live="showModalCarpoolPerson">
        <x-slot name="title">
            <h2>Aantal plaatsen</h2>
        </x-slot>
        <x-slot name="content">
            <p>Kies hoeveel plaatsen je wilt nemen.</p>
            <br>
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif

            @if(is_null($currentCarpool))

                @else
                    <x-input min="1" id="quantity" type="number" placeholder="plaatsen" wire:model="formCarpoolPerson.quantity"></x-input>
                    <x-input type="hidden" value="{{$currentCarpool->id}}" wire:model="formCarpoolPerson.carpoolID"></x-input>
            @endif

        </x-slot>
        <x-slot name="footer">
            <div class="flex gap-1">
            <x-secondary-button @click="$wire.showModalCarpoolPerson = false">Cancel</x-secondary-button>
            @if(is_null($formCarpoolPerson->id))
                <x-secondary-button wire:click="createCarpoolPerson()">Reserveren</x-secondary-button>
            @else
                <x-secondary-button color="info"
                                    wire:click="updateCarpoolPerson({{ $formCarpoolPerson->id }})"
                                    class="ml-2">Opslaan
                </x-secondary-button>
            @endif
            </div>
        </x-slot>
    </x-dialog-modal>

    {{-- Modal for add and update record --}}

    <x-dialog-modal id="recordModal"
                    wire:model.live="showModal">
        <x-slot name="title">
            <h2>{{ is_null($form->id) ? 'Nieuwe carpool' : 'Carpool bijwerken' }}</h2>
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


{{--                <x-input id="date" type="date" placeholder="datum" wire:model="form.date"></x-input>--}}
                <div class="grid grid-cols-2 gap-3">
                <x-tmk.form.select wire:model="form.date">
                    <option value="">Kies een wedstrijd/training</option>
                    @foreach($training as $train)
                        <option value="{{$train->id}}">{{$train->is_match ? "Wedstrijd" : "Training"}} - {{\Carbon\Carbon::parse($train->date)->format('d-m-Y')}} - {{\Carbon\Carbon::parse($train->time)->format('H:i')}}</option>
                    @endforeach

                </x-tmk.form.select>
                    <x-input id="address" type="text" placeholder="Address" wire:model="form.address"></x-input>
                <x-input id="hour" type="time" placeholder="Uur" wire:model="form.hour"></x-input>
                    <x-input min="1" id="quantity" type="number" placeholder="Hoeveelheid" wire:model="form.quantity"></x-input>
                </div>
        </x-slot>
        <x-slot name="footer">
            <div class="flex gap-1">
            <x-secondary-button @click="$wire.showModal = false">Cancel</x-secondary-button>
            @if(is_null($form->id))
            <x-secondary-button wire:click="createCarpool()">Maak carpool</x-secondary-button>
            @else
                <x-secondary-button color="info"
                                   wire:click="updateCarpool({{ $form->id }})"
                                   class="ml-2">Opslaan
                </x-secondary-button>
            @endif
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
