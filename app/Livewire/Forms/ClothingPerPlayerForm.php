<?php

namespace App\Livewire\Forms;

use App\Models\ClothingPerPlayer;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ClothingPerPlayerForm extends Form
{
    public $id = null;
    #[Validate(['required','exists:users,id',[
        'required' => 'Gelieve een geldige speler te selecteren!',
        'exists' => 'Deze speler bestaat niet!'
    ]], as: 'user')]
    public $user_id = null;
    #[Validate(['required','exists:clothing_sizes,id',[
        'required'=>'Gelieve een geldige kledingmaat te selecteren!',
        'exists'=>'Deze kledingmaat bestaat niet!'
    ]], as: 'clothing size')]
    public $clothing_size_id = null;

    // read the selected clothing size
    public function read($clothingPerPlayer):void
    {
        $this->id = $clothingPerPlayer->id;
        $this->user_id = $clothingPerPlayer->user_id;
        $this->clothing_size_id = $clothingPerPlayer->clothing_size_id;
    }
    // create a new clothing size
    public function create():void
    {
        $this->validate();
        ClothingPerPlayer::create([
            'user_id' => $this->user_id,
            'clothing_size_id' => $this->clothing_size_id,
        ]);
    }
    // update the selected clothing size
    public function update(ClothingPerPlayer $clothingPerPlayer):void {
        $this->validate();
        $clothingPerPlayer->update([
            'user_id' => $this->user_id,
            'clothing_size_id' => $this->clothing_size_id,
        ]);
    }
    // delete the selected clothing size
    public function delete(ClothingPerPlayer $clothingPerPlayer):void
    {
        $clothingPerPlayer->delete();
    }
}
