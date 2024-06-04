<div>

    {{-- Add button if user is admin --}}
    @if($isAdmin)
        <x-tmk.section class="mb-4 flex gap-2">
            <x-button wire:click="newAlbum()">
                + Album aanmaken
            </x-button>
        </x-tmk.section>
    @endif

    {{-- Show each album in a section --}}
    @forelse($albums->filter(function ($album) use ($isAdmin) {
        return ($album->is_visible && sizeof($album->photos) > 0) || $isAdmin;
    }) as $album)
        <x-tmk.section class="flex flex-col mb-4 gap-4">
            <div class="flex flex-wrap justify-between">
                <h2 class="text-lg font-medium text-gray-900">{{ $album->name }}</h2>
                @if ($isAdmin)
                <div>
                    <x-button
                        class="text-gray-400 hover:text-sky-100 hover:bg-gray-700 transition"
                        wire:click="toggleVisibility({{ $album }})"
                        data-tippy-content="Zichtbaarheid wijzigen">
                        @if ($album->is_visible)
                            <x-phosphor-eye class="inline-block w-5 h-5"/>
                        @else
                            <x-phosphor-eye-slash class="inline-block w-5 h-5"/>
                        @endif
                    </x-button>
                    <x-button
                        class="text-gray-400 hover:text-sky-100 hover:bg-gray-700 transition"
                        wire:click="newPhoto({{ $album }})"
                        data-tippy-content="Foto toevoegen">
                        <x-phosphor-camera-plus class="inline-block w-5 h-5"/>
                    </x-button>
                    <x-button
                        class="text-gray-400 hover:text-sky-100 hover:bg-gray-700 transition"
                        wire:click="editAlbum({{ $album }})"
                        data-tippy-content="Album bewerken">
                        <x-phosphor-pencil-line-duotone class="inline-block w-5 h-5"/>
                    </x-button>
                    <x-button
                        class="text-gray-400 hover:text-sky-100 hover:bg-gray-700 transition"
                        @click="$dispatch('swal:confirm', {
                                    html: 'Bent u zeker dat u dit album wilt verwijderen?',
                                    cancelButtonText: 'Cancel',
                                    confirmButtonText: 'Verwijder album',
                                    next: {
                                        event: 'delete-album',
                                        params: {
                                            id: {{ $album->id }}
                                        }
                                    }
                                })"
                        data-tippy-content="Album verwijderen">
                        <x-phosphor-trash-duotone class="inline-block w-5 h-5"/>
                    </x-button>
                </div>
                @endif
            </div>
            <div class="flex gap-2 flex-wrap">
                @foreach($photos as $photo)
                    @if($photo->album_id == $album->id && $photo->image !== null)
                        <div class="w-56 aspect-square relative group">
                            @if ($isAdmin)
                                <button class="absolute top-0 right-0 w-10 h-10 flex items-center justify-center rounded-bl-xl kvv-background-red transition hidden group-hover:block"
                                    @click="$dispatch('swal:confirm', {
                                    html: 'Bent u zeker dat u deze foto wilt verwijderen?',
                                    cancelButtonText: 'Cancel',
                                    confirmButtonText: 'Verwijder foto',
                                    next: {
                                        event: 'delete-photo',
                                        params: {
                                            id: {{ $photo->id }}
                                        }
                                    }
                                })">
                                <x-phosphor-trash-duotone class="inline-block w-5 h-5 pb-0.5 pl-0.5"/>
                            </button>
                            @endif
                            <img class="w-full aspect-square object-cover"
                                 src="{{ $photo->image }}"
                                 wire:click="enlargePhoto({{ $photo }})">
                        </div>
                    @endif
                @endforeach
            </div>
        </x-tmk.section>
    @empty
        <p>
            De galerij wacht rustig op nieuwe foto's.
        </p>
    @endforelse


    {{-- Modal for creating and updating an album --}}
    <x-dialog-modal id="albumModal" wire:model.live="showAlbumModal">

        <x-slot name="title">
            <h2>{{ is_null($albumForm->id) ? 'Album aanmaken' : 'Album wijzigen' }}</h2>
        </x-slot>

        <x-slot name="content">
            {{-- Errors --}}
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif

            {{-- Input fields --}}
            <x-input id="name" type="text" placeholder="Naam" wire:model="albumForm.name"></x-input>

        </x-slot>

        <x-slot name="footer">
            <div class="flex gap-2">
                <x-secondary-button @click="$wire.showAlbumModal = false">
                    Terug
                </x-secondary-button>
                @if(is_null($albumForm->id))
                    <x-secondary-button class="bg-green-400 hover:bg-green-500"
                                        wire:click="createAlbum()">
                        Maak album aan
                    </x-secondary-button>
                @else
                    <x-secondary-button class="bg-blue-300 hover:bg-blue-400" wire:click="updateAlbum({{ $albumForm->id }})">
                        Wijzig album
                    </x-secondary-button>
                @endif
            </div>
        </x-slot>

    </x-dialog-modal>


    {{-- Modal for adding a photo --}}
    <x-dialog-modal id="photoModal" wire:model.live="showPhotoModal">

        <x-slot name="title">
            <h2>Foto's toevoegen aan album "{{ $currentAlbum ? $currentAlbum->name : '' }}"</h2>
        </x-slot>

        <x-slot name="content">
            {{-- Errors --}}
            @if ($errors->any())
                <x-tmk.alert type="danger">
                    <x-tmk.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-tmk.list>
                </x-tmk.alert>
            @endif

            <label for="file-upload" class="mb-2 block cursor-pointer bg-blue-50 hover:bg-blue-100 border-gray-300 border rounded-md shadow-sm">
                <input id="file-upload" type="file" multiple
                       class="hidden"
                       wire:model="photoURLs"
                       wire:loading.attr="disabled"
                       wire:target="photoURLs"
                       accept="image/*">
                <span class="block py-2 px-4 relative w-full truncate flex gap-2 items-center">
                    <x-phosphor-upload-simple class="inline-block w-4 h-4 shrink-0" />
                    <span class="truncate">
                        @if (empty($photoURLs))
                            Kies bestand(en)...
                        @else
                            Geselecteerde bestanden ({{ count($photoURLs) }}):
                            @foreach ($photoURLs as $photoURL)
                                {{ $loop->first ? '' : ', ' }}
                                {{ $photoURL->getClientOriginalName() }}
                            @endforeach
                        @endif
                    </span>
                </span>
            </label>
            <div class="flex gap-1 flex-wrap">
                @if ($photoURLs)
                    @foreach ($photoURLs as $photoURL)
                        <img class="w-36 h-36 border border-gray-300 object-cover"
                             id="coverPreview"
                             src="{{ $photoURL->temporaryUrl() }}">
                    @endforeach
                @endif
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex gap-2">
                <x-secondary-button @click="$wire.showPhotoModal = false">
                    Terug
                </x-secondary-button>
                <x-secondary-button
                    wire:click="createPhotos()"
                    :disabled="empty($photoURLs)"
                >
                        Voeg toe
                </x-secondary-button>
            </div>
        </x-slot>

    </x-dialog-modal>


    {{-- Modal for enlarging a photo --}}
    <x-modal
        id="enlargePhotoModal"
        wire:model.live="showEnlargePhotoModal">

        @if ($currentPhoto)
            <div class="w-full" style="height: 90vh">
                <div class="relative w-full h-full flex justify-center items-center">

                    {{-- Navigation buttons --}}
                    @if (sizeof($currentAlbum->photos) > 1)
                        <div class="absolute w-full h-full z-10 flex justify-between items-center">
                            <button wire:click="previousPhoto" autofocus="false">
                                <x-phosphor-arrow-square-left class="w-10 h-10"/>
                            </button>
                            <button wire:click="nextPhoto" autofocus="false">
                                <x-phosphor-arrow-square-right class="w-10 h-10"/>
                            </button>
                        </div>
                    @endif

                    {{-- Image --}}
                    <img class="w-[85%] h-full object-contain"
                         src="{{$currentPhoto->image}}" />

                </div>
            </div>
        @endif

    </x-modal>

</div>
