<?php

namespace App\Livewire\Forms;

use App\Models\Size;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SizeForm extends Form
{
    public $id = null;
    #[Validate(['required','unique:sizes,size',[
        'required'=>'Gelieve een naam in te vullen voor de nieuwe kledingmaat!',
        'unique'=> 'Deze kledingmaat bestaat al!'
    ]], as: 'name of the size')]
    public $size = null;

    //Read the selected size
    public function read($size):void
    {
        $this->id = $size['id'];
        $this->size = $size['size'];
    }
    //Create a new size
    public function create()
    {
        $this->validate();
        return Size::create([
            'size' => $this->size,
        ])->id;
    }
    //Update the selected size
    public function update(Size $size):void {
        $this->validate();
        $size->update([
            'size' => $this->size,
        ]);
    }
    //Delete the selected size
    public function delete(Size $size):void
    {
        $size->delete();
    }
}
