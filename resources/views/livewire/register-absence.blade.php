<div class="grid lg:grid-cols-2 gap-3">
    <div class="lg:col-span-2">
    <p>Trainingen en wedstrijden</p>
    </div>
    <div>
    <div>
        <x-tmk.form.select class="mt-2" wire:model="selectedType" wire:change="updateFilteredMatchTrainings">
            <option value="training">Training</option>
            <option value="wedstrijd">Wedstrijd</option>
        </x-tmk.form.select>
        <x-tmk.form.select class="mt-2" wire:model="form.activity_id">
            <option value="null">Selecteer een {{ $this->selectedType }}</option>
            @foreach ($filteredMatchTrainings as $matchTraining)
                <option value="{{ $matchTraining->id }}">{{ \Carbon\Carbon::parse($matchTraining->date)->format('d-m-Y') }} - {{ \Carbon\Carbon::parse($matchTraining->time)->format('H:i') }}@if ($matchTraining->address) - {{ $matchTraining->address }}@endif</option>
            @endforeach
        </x-tmk.form.select>
        <x-tmk.form.select class="mt-2" wire:model="form.user_id">
            <option value="null">Selecteer kind</option>
            @foreach ($children as $child)
                <option value="{{ $child->id }}">{{ $child->first_name }} {{ $child->name }}</option>
            @endforeach
        </x-tmk.form.select>
    </div>
    <x-label for="reason" value="Reden" class="mt-4"/>
    <textarea id="reason"
             wire:model.defer="form.reason"
             class="mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm  block w-2/3"></textarea>
    <x-button
        color="success"
        wire:click="createAbsence()"
        wire:loading.attr="disabled"
        class="mt-3">Afwezigheid registreren
    </x-button>
    </div>
    <div>
        <x-tmk.section>
        <table class="text-center w-full border border-gray-300">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th wire:click="resort('activity_id')" scope="col">Activiteit</th>
                <th wire:click="resort('user_id')" scope="col">Kind</th>
                <th scope="col">Reden</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($absences as $absence)
                <tr wire:key="{{ $absence->id }}" class="border-t border-gray-300 h-16">
                        <td>{{ $absence->trainingMatch->is_match ? "Wedstrijd:" : "Training:" }} {{ \Carbon\Carbon::parse($absence->trainingMatch->date)->format('d-m-Y') }} {{ \Carbon\Carbon::parse($absence->trainingMatch->time)->format('H:i') }}</td>
                    <td>{{ $absence->user->first_name }} {{ $absence->user->name }}</td>
                    <td>{{ $absence->reason }}</td>
                    <td>
                        <button
                                @click="$dispatch('swal:confirm', {
                                        html: 'Bent u zeker dat u deze afwezigheid wilt verwijderen?',
                                        cancelButtonText: 'Cancel',
                                        confirmButtonText: 'Verwijder afwezigheid',
                                        next: {
                                            event: 'delete-absence',
                                            params: {
                                                id: {{ $absence->id }}
                                            }
                                        }
                                    })"
                            class="text-gray-400 hover:text-red-500 transition rounded w-full p-2">
                            <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border-t border-gray-300 p-4 text-center text-gray-500">
                        <div class="font-bold italic text-sky-800">Geen afwezigheden gevonden</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        </x-tmk.section>
    </div>
</div>
