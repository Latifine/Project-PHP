<?php

namespace App\Livewire\Forms;

use App\Models\Album;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AlbumForm extends Form
{

    public $id = null;
    #[Validate('required', as: 'name')]
    public $name = null;

    public function read($album)
    {
        $this->id = $album->id;
        $this->name = $album->name;
    }

    public function create()
    {
        $this->validate();
        Album::create([
            'name' => $this->name,
        ]);
    }

    public function update(Album $album)
    {
        $this->validate();
        $album->update([
            'name' => $this->name,
        ]);
    }

    public function delete(Album $album)
    {

        $album->delete();
    }
}
