<?php

namespace App\Livewire\Forms;

use App\Models\ClothingSize;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ClothingSizeForm extends Form
{
    public $id = null;
    #[Validate(['required','exists:clothing,id', [
        'required' => 'Gelieve een kledingstuk te selecteren!',
        'exists' => 'Dit kledingstuk bestaat niet!'
    ]], as: 'clothing')]
    public $clothing_id = null;
    #[Validate(['required','exists:sizes,id', [
        'required' => 'Gelieve een kledingmaat te selecteren!',
        'exists' => 'Deze kledingmaat bestaat niet!'
    ]], as: 'size')]
    public $size_id = null;

    // read the selected clothing size
    public function read($clothingSize): void
    {
        $this->id = $clothingSize['id'];
        $this->clothing_id = $clothingSize['clothing_id'];
        $this->size_id = $clothingSize['size_id'];
    }

    // create a new clothing size
    public function create($createFromExistingClothing, $createFromExistingSize): void
    {
        if ($createFromExistingClothing == True & ($this->clothing_id == null || $this->clothing_id == '')) {
            $this->addError('Geen kledingstuk geselecteerd', 'Selecteer een kledingstuk!');
        }
        if ($createFromExistingSize == True & ($this->size_id == null || $this->size_id == '')) {
            $this->addError('Geen kledingmaat geselecteerd', 'Selecteer een kledingmaat!');
        }
        // Check if a clothing size with the same 'clothing_id' and 'size_id' already exists
        if (ClothingSize::where('clothing_id', $this->clothing_id)
            ->where('size_id', $this->size_id)
            ->exists()) {
            // If a record already exists, add an error so that the validation fails
            $this->addError('Dubbele koppeling', 'Dit kledingstuk heeft al deze kledingmaat!');
        }
        $this->validate();
        ClothingSize::create([
            'clothing_id' => $this->clothing_id,
            'size_id' => $this->size_id,
        ]);
    }

    // update the selected clothing size
    public function update(ClothingSize $clothingSize): void
    {
        $this->validate();
        $clothingSize->update([
            'clothing_id' => $this->clothing_id,
            'size_id' => $this->size_id,
        ]);
    }

    // delete the selected clothing size
    public function delete(ClothingSize $clothingSize): void
    {
        $clothingSize->delete();
    }
}
