<?php

namespace App\Livewire\Forms;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PhotoForm extends Form
{

    public $id = null;
    #[Validate('required', as: 'name')]
    public $name = null;
    #[Validate('required|exists:albums,id', as: 'album')]
    public $album_id = null;

    public function read($photo)
    {
        $this->id = $photo->id;
        $this->name = $photo->name;
        $this->album_id = $photo->album_id;
    }

    public function create()
    {
        $this->validate();
        Photo::create([
            'name' => $this->name,
            'album_id' => $this->album_id,
        ]);
    }

    public function delete($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->delete($id);
    }
}
