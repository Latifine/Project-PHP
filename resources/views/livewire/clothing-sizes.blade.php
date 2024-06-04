<div>
    <x-tmk.section class="my-3" x-data="{ open: false }">
        <div class="grid grid-cols-10 gap-4">
            <div class="col-span-10">
                <x-label for="searchClothingSize" value="Filter"/>
                <div class="relative w-full md:w-2/3 lg:w-1/2">
                    <x-input id="searchClothingSize" type="text"
                             wire:model.live.debounce.500ms="searchInput"
                             wire:keydown.escape="resetValues()"
                             class="block mt-1 w-full"
                             placeholder="Zoeken naar een kledingstuk of kledingmaat"/>
                    <button
                        @click="$wire.set('searchInput', '')"
                        class="w-5 absolute right-4 top-3">
                        <x-phosphor-x/>
                    </button>
                </div>
                <x-input-error for="newClothingSize" class="m-4 -mt-4 w-full"/>
            </div>
        </div>
    </x-tmk.section>
    <x-tmk.section>
        <table class="w-full border-2 bg-gray-200 table-fixed">
            @if(isset($allClothingSizes) && count($allClothingSizes) > 0)
                <thead>
                <tr class="bg-gray-200 text-black border-b">
                    <th scope="col" class="p-2">
                        <div class="flex items-center justify-center">
                            Kledingstuk
                        </div>
                    </th>
                    <th scope="col" class="p-2">
                        <div class="flex items-center justify-center">
                            Kledingmaat
                        </div>
                    </th>
                    <th scope="col" class="p-2">
                        <div class="flex items-center justify-center">
                            <x-button
                                class="bg-sky-500 my-4 py-2 hover:text-sky-100 hover:bg-sky-700 transition border-r border-blue-600"
                                @click="$wire.set('showCreateModal', true)">
                                Voeg maat toe aan kledingstuk
                                <x-phosphor-plus class="inline-block w-5 h-5"/>
                            </x-button>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($allClothingSizes as $cs)
                    <tr class="bg-white text-black border-b" wire:key="clothing-{{ $cs->id }}">
                        <td class="px-3 py-3 text-center">
                            {{ $cs->clothing->clothing }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            {{ $cs->size->size }}
                        </td>
                        <td class="px-3 py-3 text-center justify-center">
                            <div
                                class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition items-right">
                                <x-phosphor-trash-duotone
                                    wire:click="selectThisClothingSize({{ $cs->id }})"
                                    class="w-5 text-enter text-gray-300 hover:text-red-600"/>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @else
                <tbody>
                <tr>
                    <td colspan="3" class="text-center">
                        <p>Geen kledingstuk met deze kledingmaat gevonden!</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center">
                        <div class="flex justify-center items-center">
                            <img width="240" src="/assets/icons/android-chrome-256x256.png" alt="">
                        </div>
                    </td>
                </tr>
                </tbody>
            @endif
            <x-dialog-modal wire:model="showCreateModal">
                <x-slot name="title">{{'Kledingmaat toevoegen aan kledingstuk'}}</x-slot>
                <x-slot name="content">
                    <div class="flex flex-row gap-1 mt-4">
                        <div class="flex-auto w-64">
                            <input id="clothing-checkbox" type="checkbox" value=""
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                   wire:model="selectFromExistingClothing"
                            >
                            <label for="clothing-checkbox"
                                   class="ms-2 text-sm font-medium dark:text-gray-300">
                                Bestaande kledingstuk selecteren?
                            </label>
                            <div
                                x-data="{ selectFromExistingClothing: @entangle('selectFromExistingClothing') }">
                                <div x-show="selectFromExistingClothing">
                                    <x-label for="size_id" value="Geselecteerde kledingstuk" class="mt-4"/>
                                    <x-tmk.form.select wire:model="newClothing.id" id="newClothing"
                                                       class="block mt-1 w-full">
                                        <option value="">Selecteer een kledingstuk</option>
                                        @foreach($clothing as $cl)
                                            <option value="{{$cl->id}}">{{$cl->clothing}}</option>
                                        @endforeach
                                    </x-tmk.form.select>
                                </div>
                                <div x-show="!selectFromExistingClothing">
                                    <div class="flex-1 flex-col gap-2">
                                        <x-label for="clothing" value="Naam Kledingstuk" class="mt-4"/>
                                        <x-input id="clothing" type="text" placeholder="T-shirt"
                                                 wire:model.live="newClothing.clothing"
                                                 class="mt-1 block w-full"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex-col gap-1">
                            <div class="flex-auto w-64">
                                <x-label for="size"
                                         value="{{$selectFromExistingSize ? "Kledingmaten" : "Nieuwe maat"}}"
                                         class="mt-4"/>
                                <input id="size-checkbox" type="checkbox" value=""
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                       wire:model="selectFromExistingSize"
                                >
                                <label for="size-checkbox"
                                       class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Bestaande kledingmaat gebruiken?
                                </label>
                            </div>
                            <div x-data="{ selectFromExistingSize: @entangle('selectFromExistingSize') }">
                                <div x-show="selectFromExistingSize">
                                    <x-label for="size_id" value="Geselecteerde Kledingmaat" class="mt-4"/>
                                    <x-tmk.form.select wire:model="newSize.id" id="newSize"
                                                       class="block mt-1 w-full">
                                        <option value="">Selecteer een kledingmaat</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->size }}</option>
                                        @endforeach
                                    </x-tmk.form.select>
                                </div>
                                <div x-show="!selectFromExistingSize">
                                    <div class="flex-1 flex-col gap-2">
                                        <x-label for="size" value="Naam Kledingmaat" class="mt-4"/>
                                        <x-input id="size" type="text" placeholder="Medium"
                                                 wire:model.live="newSize.size"
                                                 class="mt-1 block w-full"/>
                                    </div>
                                </div>
                            </div>
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
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 m-2 rounded-lg"
                            @click="$wire.showCreateModal = false">Sluiten
                    </button>
                    <button class="bg-sky-500 hover:bg-blue-500 text-white font-bold py-2 px-4 m-2 rounded-lg"
                            wire:click="createClothingSize()">Toevoegen
                    </button>

                </x-slot>
            </x-dialog-modal>
            @if(isset($selectedClothingSize))
                <x-dialog-modal wire:model="showDeleteModal">
                    <x-slot name="title">Ontkoppeling
                    </x-slot>
                    <x-slot name="content">
                        <h2>U staat op het punt om de kledingmaat <b>{{$selectedClothingSize->size->size}}</b> te
                            ontkoppelen van
                            het
                            kledingstuk <b>{{$selectedClothingSize->clothing->clothing}}</b>.</h2>
                        <h3 class="mt-2">Wilt u ook:</h3>
                        <div class="flex flex-row gap-1">
                            <div class="flex-auto w-64">
                                <input id="delete-clothing-checkbox" type="checkbox" value=""
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                       wire:model="deleteClothing" wire:click="updateAffectedPlayers()"
                                >
                                <label for="delete-clothing-checkbox"
                                       class="ms-2 text-sm font-medium">
                                    Het kledingstuk verwijderen?
                                </label>
                            </div>
                            <div class="flex-auto w-64">
                                <input id="delete-clothing-size-checkbox" type="checkbox" value=""
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                       wire:model="deleteClothingSize" wire:click="updateAffectedPlayers()"
                                >
                                <label for="delete-clothing-size-checkbox"
                                       class="ms-2 text-sm font-medium">
                                    De kledingmaat verwijderen?
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-row gap-1 mt-4">
                            <div x-data="{showSelectNewPlayerClothing: @entangle('showSelectNewPlayerClothing')}"
                                 class="flex-auto w-64">
                                <div x-show="showSelectNewPlayerClothing">
                                    <x-label for="newClothingSize_id"
                                             value="Deze spelers hebben een nieuw kledingstuk nodig"
                                             class="mt-4"/>
                                    <x-tmk.list id="affectedPlayers" class="block mt-1 w-full">
                                        @if(isset($affectedPlayers))
                                            @foreach($affectedPlayers as $player)
                                                <li>{{$player->playerName()}}</li>
                                            @endforeach
                                        @endif
                                    </x-tmk.list>
                                    <x-label for="newClothingSize_id"
                                             value="Gelieve een nieuw kledingstuk voor hun te selecteren"
                                             class="mt-4"/>
                                    <x-tmk.form.select wire:model="newClothingSize.id" id="newClothingSize"
                                                       class="block mt-1 w-full">
                                        @foreach($selectableClothingSizes as $clothingSize)
                                            @if($selectedClothingSize->id != $clothingSize->id)
                                                <option
                                                    value="{{ $clothingSize->id }}">{{ $clothingSize->clothing->clothing }}
                                                    - {{ $clothingSize->size->size }}</option>
                                            @endif
                                        @endforeach
                                    </x-tmk.form.select>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row gap-1 mt-4">
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
                    </x-slot>
                    <x-slot name="footer">
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 m-2 rounded-lg"
                                @click="$wire.showDeleteModal = false">Sluiten
                        </button>
                        <button class="bg-sky-500 hover:bg-blue-500 text-white font-bold py-2 px-4 m-2 rounded-lg"
                                wire:click="deleteTheClothingSize()">Ontkoppelen
                        </button>
                    </x-slot>
                </x-dialog-modal>
            @endif
        </table>
    </x-tmk.section>
</div>
