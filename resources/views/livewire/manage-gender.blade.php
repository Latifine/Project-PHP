<div>
    <x-tmk.section class="my-3" x-data="{ open: false }">
        <div class="p-4 flex justify-between items-start gap-4">
            <div class="relative">
                <span data-tippy-content="Press enter to create">
                <x-input id="newGender" type="text"
                         wire:model="newGender"
                         wire:keydown.enter="create()"
                         wire:keydown.tab="create()"
                         wire:keydown.escape="resetValues()"
                         class="block mt-1 w-64"
                         placeholder="New gender"/>
                    </span>
                <x-phosphor-arrows-clockwise wire:loading
                                             class="w-5 h-5 text-blue-500 absolute top-3 right-2 animate-spin"/>
                <x-input-error for="newGender" class="m-4 -mt-4 w-full"/>
            </div>
            <x-heroicon-o-information-circle @click="open = !open"
                                             class="w-5 text-gray-400 cursor-help outline-0"/>
        </div>
        <div
            x-show="open"
            x-transition
            style="display: none"
            class="text-sky-900 bg-sky-50 border-t p-4">
            <p>This is a cool placeholder section, lad!</p>
        </div>
    </x-tmk.section>
    <x-tmk.section>
        <table class="w-full border-2 bg-gray-200 table-fixed">
            <thead>
            <tr class="bg-gray-200 text-black border-b">
                <th scope="col" class="px-3 py-3">
                    <div class="flex items-center justify-center">
                        <span data-tippy-content="Order by Ascending">#</span>
                        <a href="#" @click="$wire.set('orderAsc',{{!$orderIdAsc }})">
                            <x-heroicon-s-chevron-up class="w-5 text-slate-400 {{$orderIdAsc ?: 'rotate-180'}}"/>
                        </a>
                    </div>
                </th>
                <th scope="col" class="px-3 py-3">
                    <div class="flex items-center justify-center">
                        <span data-tippy-content="Order by Ascending">Name</span>
                        <a href="#" @click="$wire.set('orderAsc',{{!$orderNameAsc}})">
                            <x-heroicon-s-chevron-up class="w-5 text-slate-400 {{$orderNameAsc ?: 'rotate-180'}}"/>
                        </a>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($allGenders as $g)
                <tr class="bg-white text-black border-b"
                    wire:key="gender-{{ $g->id }}"
                >
                    <td class="px-3 py-3 text-center">
                        {{$g->id}}</td>
                    <td class="px-3 py-3 text-center">
                        {{$g->gender}}
                    </td>
                    <td class="px-3 py-3 text-center">
                            <div
                                class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition items-right">
                                <x-phosphor-pencil-line-duotone
                                    wire:click="edit({{$g}})"
                                    class="w-5 text-gray-300 hover:text-green-600"/>
                                <x-phosphor-trash-duotone
                                    @click="$dispatch('swal:confirm', {
                                title: 'Delete {{ $g->gender }}?',
                                icon: 'warning',
                                background: 'error',
                                html: '{{'<b>ATTENTION</b>: you are going to delete <b>' . $g->gender . '</b> for good! Are you sure?'}}',
                                color: 'red',
                                cancelButtonText: 'NO!',
                                confirmButtonText: 'YES DELETE THIS GENDER',
                                next: {
                                    event: 'delete-gender',
                                    params: {
                                        id: {{ $g->id }}
                                    }
                                }
                            })" class="w-5 text-gray-300 hover:text-red-600"/>
                            </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-tmk.section>
</div>
