<div class="overflow-x-auto">
<x-tmk.section class="mb-4 flex justify-between">
        <x-button class="py-3" wire:click="newChild()">
            Kind toevoegen
        </x-button>
    </x-tmk.section>
    @if($children->isEmpty())
        <x-tmk.alert type="info" class="w-full">
            U hebt momenteel geen kinderen.
        </x-tmk.alert>
    @else
     <x-tmk.section>
    <table class="text-center w-full border border-gray-300 mt-3">
        <thead>
        <tr class="bg-gray-100 text-gray-700">
            <th>Voornaam</th>
            <th>Naam</th>
            <th>Geboortedatum</th>
            <th>Maat</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($children as $child)
            <tr class="border-t border-gray-300 h-16">
                <td>{{ $child->first_name }}</td>
                <td>{{ $child->name }}</td>
                <td>{{ \Carbon\Carbon::parse($child->date_of_birth)->format('d-m-Y') }}</td>
                <td>
                    {{-- Check if clothingPerPlayer relationship exists --}}
                    @if($child->clothingPerPlayer->isNotEmpty())
                        {{-- Loop through each clothingPerPlayer instance --}}
                        @foreach($child->clothingPerPlayer as $clothingPerPlayer)
                            {{-- Access clothingSize for each clothingPerPlayer instance --}}
                            {{ optional($clothingPerPlayer->clothingSize)->size->size ?? 'No Size Available' }}
                        @endforeach
                    @else
                        No Clothing Information
                    @endif
                </td>
                <td>
                    <div class="border border-gray-300 rounded-md overflow-hidden m-2 grid grid-cols-1 h-10">
                        <button
                            wire:click="editChild({{ $child->id }})"
                            class="text-gray-400 hover:text-sky-100 hover:bg-sky-500 transition">
                            <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                        </button>
{{--                        <button--}}
{{--                            @click="confirm('Bent u zeker dat u uw kind wilt verwijderen?') ? $wire.deleteChild({{ $child }}) : ''"--}}
{{--                            @click="$dispatch('swal:confirm', {--}}
{{--                                        html: 'Bent u zeker dat u dit kind wilt verwijderen?',--}}
{{--                                        cancelButtonText: 'Cancel',--}}
{{--                                        confirmButtonText: 'Verwijder kind',--}}
{{--                                        next: {--}}
{{--                                            event: 'delete-child',--}}
{{--                                            params: {--}}
{{--                                                id: {{ $child->id }}--}}
{{--                                            }--}}
{{--                                        }--}}
{{--                                    })"--}}
{{--                            @click="confirm('Bent u zeker dat u uw kind wilt verwijderen?') ? $wire.deleteChild({{ $child }}) : ''"--}}
{{--                            class="text-gray-400 hover:text-red-100 hover:bg-red-500 transition">--}}
{{--                            <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>--}}
{{--                        </button>--}}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </x-tmk.section>
     @endif
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">Kind Toevoegen</x-slot>
        <x-slot name="content">
            <div class="mt-4">
                <x-label for="first_name" value="{{ __('Voornaam') }}" />
                <x-input wire:model.defer="form.first_name" id="first_name" class="block mt-1 w-full" type="text" name="child_first_name" required autofocus autocomplete="given-name" />
                @error('form.first_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-label for="name" value="{{ __('Naam') }}" />
                <x-input wire:model.defer="form.name" id="last_name" class="block mt-1 w-full" type="text" name="name" required autocomplete="family-name" />
                @error('form.last_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-label for="date_of_birth" value="{{ __('Geboortedatum') }}" />
                <x-input wire:model.defer="form.date_of_birth" id="date_of_birth" class="block mt-1 w-full" type="date" name="child_date_of_birth" required />
                @error('form.date_of_birth') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-label for="size" value="{{ __('Maat') }}" />
                <x-tmk.form.select wire:model.defer="form.size_id" id="size" class="block mt-1 w-full" name="size_id" required>
                    <option value="">Selecteer Maat</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}" @if($form->size_id == $size->id) selected @endif>{{ $size->size }}</option>
                    @endforeach
                </x-tmk.form.select>
                @error('form.size_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4">
                <x-label for="child_gender" value="{{ __('Geslacht') }}" />
                <x-tmk.form.select wire:model.defer="form.gender_id" id="child_gender" class="block mt-1 w-full" name="child_gender" required>
                    <option value="">Selecteer Geslacht</option>
                    @foreach($genders as $gender)
                        <option value="{{ $gender->id }}">{{ $gender->gender }}</option>
                    @endforeach
                </x-tmk.form.select>
                @error('form.gender_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button @click="$wire.showModal = false">Annuleer</x-secondary-button>
            @if(is_null($form->id))
                <x-button
                    wire:click="createChild()"
                    wire:loading.attr="disabled"
                    class="ml-2">Kind toevoegen
                </x-button>
            @else
                <x-button
                    color="success"
                    wire:click="updateChild({{ $form->id }})"
                    wire:loading.attr="disabled"
                    class="ml-2">Opslaan
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>


