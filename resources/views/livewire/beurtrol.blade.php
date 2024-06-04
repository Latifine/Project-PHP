<div>

    @if ($isAdmin)

        <x-tmk.section class="w-full mb-4 flex justify-between">
            <x-button wire:click="newBeurtrol()">
                + Beurtrol aanmaken
            </x-button>
            <x-button
                class="px-[0.5rem] py-[0.375rem]"
                wire:click="newRol()"
                data-tippy-content="Taak toevoegen">
                <x-phosphor-plus-square class="inline-block w-5 h-5"/>
            </x-button>
        </x-tmk.section>

    @endif

    <x-tmk.section>
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                @if($isAdmin)
                    <col>
                @endif
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th>Training/Match</th>
                <th>Adres</th>
                <th>Datum</th>
                <th>Rol</th>
                <th>Plaatsen</th>
                <th></th>
                @if($isAdmin)
                    <th></th>
                @endif
            </tr>
            </thead>
            <tbody>
            @if($beurtrollen->isEmpty())
                <tr>
                    <td colspan="7" class="border-t border-gray-300 p-4 text-center text-gray-500">
                        <div class="font-bold italic text-sky-800">Geen beurtrollen beschikbaar</div>
                    </td>
                </tr>
            @else
                @foreach($beurtrollen as $beurtrol)
                        <tr class="border-t border-gray-300 h-16">
                            @php
                                foreach ($activiteiten as $activiteit)
                                    {
                                        if ($activiteit->id == $beurtrol->activity_id)
                                            {
                                                $varactiviteit = $activiteit;
                                                break;
                                            }

                                    }

                                foreach ($rollen as $rol)
                                    {
                                        if ($rol->id == $beurtrol->task_id)
                                            {
                                                $varrol = $rol;
                                                break;
                                            }

                                    }
                                $used = false;
                                $isnotintask = true;
                                foreach ($peoplepertasks as $peopletask)
                                {
                                    if (\Illuminate\Support\Facades\Auth::user()->id == $peopletask->user_id && $beurtrol->id == $peopletask->task_per_activity_id)
                                    {
                                        $isnotintask = false;
                                        break;
                                    }
                                }

                            @endphp
                            <td>{{$varactiviteit->is_match ? "Wedstrijd" : "Training"}}</td>
                            <td>{{$varactiviteit->address}}</td>
                            <td>{{\Carbon\Carbon::parse($varactiviteit->date)->format('d-m-Y')}}</td>
                            <td>{{$varrol->task}}</td>
                            <td>{{$beurtrol->quantity}}</td>
                            <td>
                                @if($beurtrol->quantity > 0 && $isnotintask)
                                    <x-button wire:click="newPersonPerActivity({{$beurtrol}})">Beschikbaar</x-button>
                                @endif</td>
                            @if($isAdmin)
                                <td>
                                    <div class="border border-gray-300 rounded-md overflow-hidden m-2 grid grid-cols-2 h-10"><button
                                            wire:click="editBeurtrol({{$beurtrol}})"
                                            class="text-gray-400 hover:text-sky-100 hover:bg-sky-500 transition border-r border-gray-300">
                                            <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                                        </button>
                                        <button
                                            @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u deze taak wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder taak',
                                        next: {
                                            event: 'delete-taak',
                                            params: {
                                                id: {{ $beurtrol->id }}
                                            }
                                        }
                                    })"
                                            {{--                            wire:click="deleteTaskPerActivity({{$beurtrol}})"--}}
                                            {{--                            wire:confirm="Ben je zeker dat je deze beurtrol wilt verwijderen?"--}}
                                            class="text-gray-400 hover:text-red-100 hover:bg-red-500 transition">
                                            <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </x-tmk.section>

    <x-tmk.section class="mt-5">
        <table class="text-center w-full border border-gray-300">
            <colgroup>

                <col class="">
                <col class="">
                <col class="">
                <col class="">
                <col>
                <col>
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 ">

                @if($isAdmin)
                    <th>naam</th>
                @else
                    <th>Rol</th>
                @endif

                <th>Adres</th>
                <th>Training/Wedstrijd</th>
                <th>Mededeling</th>
                <th>Actief</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            @if($peoplepertasks->isEmpty())
                <tr>
                    <td colspan="5" class="border-t border-gray-300 p-4 text-center text-gray-500">
                        <div class="font-bold italic text-sky-800">Geen aanvraag voor een beurtrol gevonden</div>
                    </td>
                </tr>
            @else
                @foreach($peoplepertasks as $mens)
                    @if($isAdmin)
                        <tr class="border-t border-gray-300 h-10">
                            @php
                                    foreach ($users as $user)
                                        if($user->id == $mens->user_id)
                                            $varuser = $user;
                                    foreach ($beurtrollen as $beurtrol)
                                        if($mens->task_per_activity_id == $beurtrol->id)
                                            $varbeurtrol = $beurtrol;
                                    foreach ($activiteiten as $activiteit)
                                        if ($activiteit->id == $varbeurtrol->activity_id)
                                            $varactiviteit = $activiteit;
                                    foreach ($rollen as $rol)
                                        if ($rol->id == $varbeurtrol->task_id)
                                            $varrol = $rol;
                            @endphp

                            <td>{{$varuser->first_name}} {{$varuser->name}} </td>
                            <td>{{$varrol->task}}</td>
                            <td>{{$varactiviteit->is_match ? "Wedstrijd" : "Training"}} - {{\Carbon\Carbon::parse($varactiviteit->date)->format('d-m-Y')}} {{$varactiviteit->is_match ? "-" : ""}} {{$varactiviteit->address}}</td>
                            <td>{{$mens->reason_exceptional_circumstance}}</td>
                            @if($isAdmin)
                                <td>
                                    {{--                        <x-tmk.form.switch id="isActief"--}}
                                    {{--                                           wire:click="updatePersonPerTask()"--}}
                                    {{--                                           checked="{{$mens->is_assigned}}"--}}
                                    {{--                                           color-off="text-white bg-red-500"--}}
                                    {{--                                           color-on="text-white bg-lime-600"--}}
                                    {{--                                            /></td>--}}
                                    <button
                                        @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat je deze aanvraag wilt updaten? De gebruiker zal een mail krijgen van de wijziging',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Update aanvraag',
                                        next: {
                                            event: 'update-status',
                                            params: {
                                                id: {{ $mens->id }}
                                            }
                                        }
                                    })"
                                        class="text-white {{$mens->is_assigned ? "bg-green-600 hover:bg-green-700" : "bg-red-600 hover:bg-red-700" }} hover:text-gray-100 transition rounded-lg w-3/4 border-2 mt-1">
                                        @if($mens->is_assigned)
                                            Actief
                                        @else
                                            Inactief
                                        @endif
                                    </button>
                                </td>
                            @else
                                <td>
                                    {{--                        <x-tmk.form.switch id="isActief"--}}
                                    {{--                                           wire:click="updatePersonPerTask()"--}}
                                    {{--                                           checked="{{$mens->is_assigned}}"--}}
                                    {{--                                           color-off="text-white bg-red-500"--}}
                                    {{--                                           color-on="text-white bg-lime-600"--}}
                                    {{--                                            /></td>--}}

                                    @if($mens->is_assigned)
                                        <div class="text-green-500">
                                            &#x2713;
                                        </div>
                                    @else
                                        <div class="text-red-500">
                                            &#x2717;
                                        </div>
                                    @endif
                                </td>
                            @endif



                            <td><button
                                    @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u zich niet langer beschikbaar wilt stellen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Stel niet meer beschikbaar',
                                        next: {
                                            event: 'delete-aanvraag',
                                            params: {
                                                id: {{ $mens->id }}
                                            }
                                        }
                                    })"
                                    class="text-gray-400 hover:text-red-500 transition rounded w-full">
                                    <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                                </button></td>

                        </tr>
                    @else
                        @if(\Illuminate\Support\Facades\Auth::user()->id == $mens->user_id)
                            <tr class="border-t border-gray-300 h-10">
                                @php
                                    foreach ($beurtrollen as $beurtrol)
                                        if($mens->task_per_activity_id == $beurtrol->id)
                                            $varbeurtrol = $beurtrol;
                                    foreach ($activiteiten as $activiteit)
                                        if ($activiteit->id == $varbeurtrol->activity_id)
                                            $varactiviteit = $activiteit;
                                    foreach ($rollen as $rol)
                                        if ($rol->id == $varbeurtrol->task_id)
                                            $varrol = $rol;

                                @endphp

                                <td>{{$varrol->task}}</td>
                                <td>{{$varactiviteit->address}}</td>
                                <td>{{$varactiviteit->is_match ? "Wedstrijd" : "Training"}} - {{\Carbon\Carbon::parse($varactiviteit->date)->format('d-m-Y')}}</td>
                                <td>{{$mens->reason_exceptional_circumstance}}</td>
                                @if($isAdmin)
                                    <td>
                                        {{--                        <x-tmk.form.switch id="isActief"--}}
                                        {{--                                           wire:click="updatePersonPerTask()"--}}
                                        {{--                                           checked="{{$mens->is_assigned}}"--}}
                                        {{--                                           color-off="text-white bg-red-500"--}}
                                        {{--                                           color-on="text-white bg-lime-600"--}}
                                        {{--                                            /></td>--}}
                                        <button
                                            @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat je deze aanvraag wilt updaten? De gebruiker zal een mail krijgen van de wijziging',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Update aanvraag',
                                        next: {
                                            event: 'update-status',
                                            params: {
                                                id: {{ $mens->id }}
                                            }
                                        }
                                    })"
                                            class="text-white {{$mens->is_assigned ? "bg-green-600 hover:bg-green-700" : "bg-red-600 hover:bg-red-700" }} hover:text-gray-100 transition rounded-lg w-3/4 border-2 mt-1">
                                            @if($mens->is_assigned)
                                                Actief
                                            @else
                                                Inactief
                                            @endif
                                        </button>
                                    </td>
                                @else
                                    <td>
                                        {{--                        <x-tmk.form.switch id="isActief"--}}
                                        {{--                                           wire:click="updatePersonPerTask()"--}}
                                        {{--                                           checked="{{$mens->is_assigned}}"--}}
                                        {{--                                           color-off="text-white bg-red-500"--}}
                                        {{--                                           color-on="text-white bg-lime-600"--}}
                                        {{--                                            /></td>--}}

                                        @if($mens->is_assigned)
                                            <div class="text-green-500">
                                                &#x2713;
                                            </div>
                                        @else
                                            <div class="text-red-500">
                                                &#x2717;
                                            </div>
                                        @endif
                                    </td>
                                @endif



                                <td><button
                                        @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u zich niet langer beschikbaar wilt stellen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Stel niet meer beschikbaar',
                                        next: {
                                            event: 'delete-aanvraag',
                                            params: {
                                                id: {{ $mens->id }}
                                            }
                                        }
                                    })"
                                        class="text-gray-400 hover:text-red-500 transition rounded w-full">
                                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                                    </button></td>

                            </tr>
                        @endif
                    @endif

                @endforeach
            @endif

            </tbody>
        </table>
    </x-tmk.section>

    <x-dialog-modal id="mededelingModal"
    wire:model.live="showModal">
        <x-slot name="title">
            Mededeling (optioneel)
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
            <x-input type="text" placeholder="Mededeling" wire:model="form.reason_exceptional_circumstance"></x-input>
        </x-slot>
        <x-slot name="footer">
            <div class="flex gap-1">
            <x-secondary-button @click="$wire.showModal = false">Cancel</x-secondary-button>
            <x-secondary-button wire:click="createPersonPerActivity">aanvragen</x-secondary-button>
            </div>
        </x-slot>

    </x-dialog-modal>

    <x-dialog-modal id="rolModal"
                    wire:model.live="showTaakModal">
        <x-slot name="title">
            <h2>Taak toevoegen</h2>
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
            <x-input id="task" type="text" placeholder="Taak" wire:model="taskForm.task"></x-input>
        </x-slot>
        <x-slot name="footer">
            <div class="flex gap-1">
                <x-secondary-button @click="$wire.showTaakModal = false">Annuleer</x-secondary-button>
                <x-secondary-button wire:click="createRol()">Aanmaken</x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal id="beurtrolModal"
                    wire:model.live="showBeurtrolModal">
        <x-slot name="title">

            <h2>{{ is_null($beurtrolForm) ? 'Beurtrol toevoegen' : 'Beurtrol bewerken'}}</h2>
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
            <div class="grid grid-cols-2 gap-3">
                <x-tmk.form.select wire:model="beurtrolForm.activity_id">
                    <option value="">Kies een activiteit</option>
                    @foreach($activiteiten as $activiteit)
                        @if (Carbon\Carbon::parse($activiteit->date)->greaterThanOrEqualTo(\Carbon\Carbon::today()))
                            <option name="option" id="option" value="{{$activiteit->id}}">
                                @if ($activiteit->is_match)
                                    Wedstrijd: {{$activiteit->address}} - {{\Carbon\Carbon::parse($activiteit->date)->format('d-m-Y')}}
                                @else
                                    Training: {{\Carbon\Carbon::parse($activiteit->date)->format('d-m-Y')}}
                                @endif
                            </option>
                        @endif
                    @endforeach

                </x-tmk.form.select>

                <x-tmk.form.select wire:model="beurtrolForm.task_id">
                    <option value="">Kies beurtrol</option>
                    @foreach($rollen as $rol)
                        <option name="rol" id="rol" value="{{$rol->id}}">{{$rol->task}}</option>
                    @endforeach
                </x-tmk.form.select>

                <x-input type="number" min="1" wire:model="beurtrolForm.quantity" placeholder="Plaatsen"></x-input>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="flex gap-1">
                <x-secondary-button @click="$wire.showBeurtrolModal = false">Annuleer</x-secondary-button>
                @if(is_null($beurtrolForm->id))
                    <x-secondary-button wire:click="createBeurtrol()">Aanmaken</x-secondary-button>
                @else
                    <x-secondary-button color="info"
                                        wire:click="updateBeurtrol({{ $beurtrolForm->id }})"
                                        class="ml-2">Opslaan
                    </x-secondary-button>
                @endif
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
