<?php

namespace App\Livewire;

use App\Livewire\Forms\AlbumForm;
use App\Livewire\Forms\PhotoForm;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Photo extends Component
{

    use WithFileUploads;

    public AlbumForm $albumForm;
    public PhotoForm $photoForm;

    public bool $showAlbumModal;
    public bool $showPhotoModal;
    public bool $showEnlargePhotoModal;

    public Album $currentAlbum;
    public \App\Models\Photo $currentPhoto;
    public int $currentPhotoIndex;

    # Empty array for URLs of photos in photo modal
    #[Validate('required|array|min:1', '*.required|image|mimes:jpg,jpeg,png,webp|max:1024')]
    public array $photoURLs = [];

    #[Layout('layouts.app', [
        'title' => "Galerij",
        'subtitle' => 'Galerij',
        'description' => 'Hier kan je al de foto\'s bekijken',
        'developer' => 'Jarne Vermant'

        ])]
    public function render() {
        $albums = \App\Models\Album::orderbydesc('created_at')
            ->get();
        $photos = \App\Models\Photo::orderbydesc('created_at')
            ->get();
        $user = Auth::user();

        // Check if the user's role is "Admin"
        $isAdmin = false;
        if ($user) {
            $isAdmin = $user->role()->where('role', 'Admin')->exists();
        }

        return view('livewire.photo', compact('albums', 'photos', 'isAdmin'));
    }

    public function newAlbum() {
        //$this->albumForm->reset();
        $this->resetErrorBag();
        $this->showAlbumModal = true;
    }

    public function createAlbum() {
        $this->albumForm->create();
        $this->showAlbumModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Het album " . $this->albumForm->name . " is aangemaakt.",
            'icon' => 'success',
        ]);
        $this->albumForm->reset();
    }

    public function editAlbum(\App\Models\Album $album) {
        $this->resetErrorBag();
        $this->albumForm->fill($album);
        $this->showAlbumModal = true;
    }

    public function updateAlbum(\App\Models\Album $album) {
        $this->albumForm->update($album);
        $this->showAlbumModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Het album is gewijzigd.",
            'icon' => 'success',
        ]);
    }

    #[On('delete-album')]
    public function deleteAlbum($id) {
        $album = Album::findOrFail($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Het album is verwijderd.",
            'icon' => 'success',
        ]);
        $this->albumForm->delete($album);
    }

    public function toggleVisibility(\App\Models\Album $album) {
        $album->is_visible = !$album->is_visible;
        $album->save();
    }

    public function newPhoto(\App\Models\Album $album) {
        $this->photoForm->reset();
        $this->resetErrorBag();
        $this->currentAlbum = $album;
        $this->showPhotoModal = true;
    }

    public function createPhotos() {

        foreach ($this->photoURLs as $photoURL) {
            $this->savePhotoToDisk($photoURL);
            $this->photoForm->create();
        }

        $this->reset($this->currentAlbum);
        $this->showPhotoModal = false;

        $message = "De foto's zijn toegevoegd.";
        if (sizeof($this->photoURLs) === 1) {
            $message = "De foto is toegevoegd.";
        }

        $this->photoURLs = [];

        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => $message,
            'icon' => 'success',
        ]);
    }

    #[On('delete-photo')]
    public function deletePhoto($id) {

        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De foto is verwijderd.",
            'icon' => 'success',
        ]);
        $this->photoForm->delete($id);
    }

    public function savePhotoToDisk($photo) {
        $albumName = $this->currentAlbum->id;

        $this->photoForm->name = uniqid();
        $this->photoForm->album_id = $albumName;
        $imageName = $this->photoForm->name . '.jpg';

        $image = Image::make($photo->getRealPath())->encode('jpg', 75);

        Storage::put("public/photos/{$albumName}/{$imageName}", $image, 'public');
    }

    public function enlargePhoto(\App\Models\Photo $photo) {
        $this->currentAlbum = $photo->album;
        $this->currentPhoto = $photo;
        $this->showEnlargePhotoModal = true;

        $this->currentPhotoIndex = $this->findCurrentPhotoIndex($photo);
    }

    private function findCurrentPhotoIndex($photo) {
        $photos = $this->currentAlbum->photos->sortByDesc('created_at');
        return $photos->search(function ($item) use ($photo) {
            return $item->id === $photo->id;
        });
    }

    public function previousPhoto() {
        $photos = $this->currentAlbum->photos;

        if ($this->currentPhotoIndex < sizeof($photos) - 1) {
            $this->currentPhotoIndex++;
        }
        else {
            $this->currentPhotoIndex = 0;
        }

        $this->currentPhoto = $photos[$this->currentPhotoIndex];
    }

    public function nextPhoto() {
        $photos = $this->currentAlbum->photos;

        if ($this->currentPhotoIndex > 0) {
            $this->currentPhotoIndex--;
        }
        else {
            $this->currentPhotoIndex = sizeof($photos) - 1;
        }

        $this->currentPhoto = $photos[$this->currentPhotoIndex];
    }

}
