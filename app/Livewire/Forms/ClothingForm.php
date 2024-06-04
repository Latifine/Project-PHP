<?php

namespace App\Livewire\Forms;

use App\Models\Clothing;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ClothingForm extends Form
{
    public $id = null;
    #[Validate(['required','min:2','unique:clothing,clothing',[
        'required'=>'Gelieve een naam voor het kledingstuk in te vullen!',
        'unique'=> 'Een kledingstuk met deze naam bestaat al!',
        'min'=> 'De naam van een kledingstuk moet minstens 2 tekens lang zijn!'
    ]], as: 'name of the clothing')]
    public $clothing = null;

    //Read the selected Clothing
    public function read($clothing):void
    {
        $this->id = $clothing['id'];
        $this->clothing = $clothing['clothing'];
    }
    //Create a new Clothing
    public function create()
    {
        $this->validate();
        return Clothing::create([
            'clothing' => $this->clothing,
        ])->id;
    }
    //Update the selected Clothing
    public function update(Clothing $clothing):void {
        $this->validate();
        $clothing->update([
            'clothing' => $this->clothing,
        ]);
    }
    //Delete the selected Clothing
    public function delete(Clothing $clothing):void
    {
        $clothing->delete();
    }
}
