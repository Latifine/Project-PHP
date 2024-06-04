<?php

namespace App\Livewire;

use App\Livewire\Forms\ClothingForm;
use App\Livewire\Forms\ClothingPerPlayerForm;
use App\Livewire\Forms\ClothingSizeForm;
use App\Livewire\Forms\SizeForm;
use App\Models\Clothing;
use App\Models\ClothingPerPlayer;
use App\Models\ClothingSize;
use App\Models\Size;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ClothingSizes extends Component
{
    use WithPagination;

    public $orderByClothingSizeId = 'id';
    public $orderByClothingId = 'clothing_id';

    //Validation rules:
    //Size: Size Needs to at least be one character long and has to be unique
    //Existing Size: Id can't be null
    //Clothing: Clothing Needs to at least be 3 character long and has to be unique
    //Existing Clothing: Id can't be null
    //Clothing Size: The size hasn't already been added to the clothing
    public $newClothing = [
        'id' => null,
        'clothing' => null,
    ];
    public $newSize = [
        'id' => null,
        'size' => null,
    ];
    public $newClothingSize = [
        'id' => null,
        'clothing_id' => null,
        'size_id' => null,
    ];
    public array|string $errorList = [];
    public $orderClothingSizeIdAsc = true;
    public $orderClothingIdAsc;
    public $showCreateModal;
    public $showDeleteModal;
    public $selectFromExistingSize = true;
    public $selectFromExistingClothing = true;
    public $deleteClothing = false;
    public $deleteClothingSize = false;
    public $selectedClothingSize;
    public $affectedPlayers;
    public string $searchInput = "";
    public $selectableClothingSizes;
    public $showSelectNewPlayerClothing;

    //Forms
    public ClothingForm $clothingForm;
    public SizeForm $sizeForm;
    public ClothingSizeForm $clothingSizeForm;
    public ClothingPerPlayerForm $clothingPerPlayerForm;

    #[Layout('layouts.app', [
        'title' => "Kledij",
        'description' => 'Kledij van de spelers beheren',
        'developer' => 'Jorrit Janssens'
    ])]

    public function render()
    {
        $this->showSelectNewPlayerClothing = $this->doesAnyPlayerNeedNewClothing();

        $allClothingSizes = (new ClothingSize())
            ->has('clothing')
            ->has('size')
            ->searchClothingOrSize($this->searchInput)
            ->get();
        $sizes = (new Size)
            ->get();
        $clothing = (new Clothing)
            ->get();

        $clothingPerPlayer = (new ClothingPerPlayer());
        return view('livewire.clothing-sizes', compact('sizes', 'clothing', 'clothingPerPlayer', 'allClothingSizes'))->with('doesAnyPlayerNeedNewClothing', $this->doesAnyPlayerNeedNewClothing());
    }

    public function resetValues(): void
    {
        $this->reset('newSize', 'newClothing', 'newClothingSize', 'errorList',
            'selectedClothingSize', 'clothingSizeForm', 'clothingForm', 'sizeForm', 'clothingPerPlayerForm');
        $this->resetErrorBag();
    }

    public function resort($column): void
    {
        if ($this->orderByClothingSizeId === $column) {
            $this->orderClothingSizeIdAsc = !$this->orderClothingSizeIdAsc;
        } else {
            $this->orderClothingSizeIdAsc = true;
        }
        $this->orderByClothingSizeId = $column;
    }

    public function updated($property, $value): void
    {
//         $property: The name of the current property being updated
//         $value: The value about to be set to the property
        if (in_array($property, ['searchInput']))
            $this->resetPage();
    }


    public function createClothingSize(): void
    {
        //If an existing clothing piece was used instead of creating a new one, find the name of it
        if ($this->newClothing['clothing'] == "" && $this->newClothing['id'] != 0) {
            $this->newClothing['clothing'] = Clothing::findOrFail($this->newClothing['id'])->clothing;
        }
        //If an existing clothing size was used instead of creating a new one, find the name of it
        if ($this->newSize['size'] == "" && $this->newSize['id'] != 0) {
            $this->newSize['size'] = Size::findOrFail($this->newSize['id'])->size;
        }

        //Read in the new clothing
        $this->clothingForm->read($this->newClothing);
        $this->sizeForm->read($this->newSize);
        try {
            $this->reset('errorList');
            //check if new clothing is on
            if (!$this->selectFromExistingClothing) {
                //Add the new clothing to the ClothingSize object and create it
                $this->newClothingSize['clothing_id'] = $this->clothingForm->create();
            } else {
                $this->newClothingSize['clothing_id'] = $this->clothingForm->id;
            }
            //check if new size is on
            if (!$this->selectFromExistingSize) {
                //Add the new size to the ClothingSize object and create it
                $this->newClothingSize['size_id'] = $this->sizeForm->create();
            } else {
                $this->newClothingSize['size_id'] = $this->sizeForm->id;
            }
            //Read in the ClothingSize
            $this->clothingSizeForm->read($this->newClothingSize);
            // Proceed with creating the new clothing size record
            $this->clothingSizeForm->create($this->selectFromExistingClothing, $this->selectFromExistingSize);
        } catch (Exception $exception) {
            // Capture the exception message and store it in a variable
            $errorMessage = $exception->getMessage();

            foreach ($this->errorList as $error) {
                $this->addError('Error!', $error);
            }
            if ($errorMessage != "") {
                $this->addError('Error!', $errorMessage);
            }
            return;
        }
        //show create modal false
        $this->showCreateModal = false;
        //dispatch swal
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De kledingmaat <b><i>{$this->sizeForm->size}</i></b> is toegevoegd aan het kledingstuk <b><i>{$this->clothingForm->clothing}</i></b>.",
        ]);
        //Reset everything
        $this->resetValues();
    }

    public function selectThisClothingSize($id): void
    {
        $this->showDeleteModal = true;
        $this->selectedClothingSize = ClothingSize::findOrFail($id);
        $this->updateAffectedPlayers();
    }

    public function doesAnyPlayerNeedNewClothing()
    {
        if ($this->affectedPlayers == NULL) {
            return false;
        }
        return $this->affectedPlayers->count() > 0;
    }

    public function updateAffectedPlayers(): void
    {
        //Get all the selected ones
        $this->affectedPlayers = $this->selectedClothingSize->clothingPerPlayer;
        //Get all the remaining ones based on the boolean selected and filter out any players that are already in the list
        //Check if delete clothing selected
        $selectedClothingId = 0;
        $selectedSizeId = 0;
        if ($this->deleteClothing) {
            $selectedClothingId = $this->selectedClothingSize->clothing->id;
        }
        if ($this->deleteClothingSize) {
            $selectedSizeId = $this->selectedClothingSize->size->id;
        }
        // Eager load the clothingPerPlayer relationship
        if ($selectedClothingId != 0 || $selectedSizeId != 0) {
            $temporaryClothingSizeCollection = ClothingSize::where(function ($query) use ($selectedClothingId, $selectedSizeId) {
                if ($selectedClothingId != 0 && $selectedSizeId != 0) {
                    $query->where('clothing_id', 'like', $selectedClothingId)->orWhere('size_id', 'like', $selectedSizeId);
                } else if ($selectedClothingId != 0) {
                    $query->where('clothing_id', 'like', $selectedClothingId);
                } else if ($selectedSizeId != 0) {
                    $query->where('size_id', 'like', $selectedSizeId);
                }
            })->with('clothingPerPlayer')->get();
            foreach ($temporaryClothingSizeCollection as $cs) {
                //In loop since a player could be added after each loop
                $playerIdsInAffectedPlayers = $this->affectedPlayers->pluck('user_id')->toArray();

                //Get and filter out the users that are already in the list by checking if their id is in there
                $players = $cs->clothingPerPlayer()->whereNotIn('user_id', $playerIdsInAffectedPlayers)->get();

                // Merge with affectedPlayers
                $this->affectedPlayers = $this->affectedPlayers->merge($players);
            }
            $this->showSelectNewPlayerClothing = $this->doesAnyPlayerNeedNewClothing();
        }

        $this->selectableClothingSizes = ClothingSize::whereNot(function ($query) use ($selectedClothingId, $selectedSizeId) {
            if ($selectedClothingId != 0 && $selectedSizeId != 0) {
                $query->where('clothing_id', 'like', $selectedClothingId)->orWhere('size_id', 'like', $selectedSizeId);
            } else if ($selectedClothingId != 0) {
                $query->where('clothing_id', 'like', $selectedClothingId);
            } else if ($selectedSizeId != 0) {
                $query->where('size_id', 'like', $selectedSizeId);
            }
        })->with('clothingPerPlayer')->get();
    }

    public function deleteTheClothingSize(): void
    {
        $this->showDeleteModal = false;
        //dispatch swal
        $this->dispatch('swal:confirm', [
            'title' => "Waarschuwing! U staat het op het punt om verschillende wijzigingen door te voeren. Bent u zeker?",
            'icon' => 'warning',
            'background' => 'error',
            'color' => 'red',
            'cancelButtonText' => 'Nee, liever niet.',
            'confirmButtonText' => 'Ja! Weg ermee!',
            'next' => [
                'event' => 'delete-clothing-size',
                'params' => [
                    'id' => $this->selectedClothingSize->id
                ]
            ]
        ]);
    }

    // delete a clothing size
    #[On('delete-clothing-size')]
    public function delete($id): void
    {
        try {
            $newClothingSizeIdForPlayer = $this->newClothingSize['id'];
            //Update all the affected players based on the selected clothing piece
            foreach ($this->affectedPlayers as $player) {
                //If the player already has the uniform with that size
                $playerId = $player->user_id;
                if (ClothingPerPlayer::whereHas('clothingSize', function ($query) use ($newClothingSizeIdForPlayer, $playerId) {
                    $query->where('id', $newClothingSizeIdForPlayer)->where('user_id', $playerId);
                })->exists()) {
                    //Make them not have it
                    $player->delete();
                } else {
                    //Update clothing size object attached to the players in question
                    //Check if new clothing already has these players
                    $player->clothing_size_id = $this->newClothingSize['id'];
                    $player->save();
                }
            }
            $clothingSize = ClothingSize::findOrFail($id);
            //Names are assigned here because they will not be shown otherwise when deleted
            $nameSize = $clothingSize->size->size;
            $nameClothing = $clothingSize->clothing->clothing;

            $clothingSize->delete();
            //Delete the clothing if this was selected
            if ($this->deleteClothing) {
                $clothingSize->clothing()->delete();
            }
            //Delete the size if this was selected
            if ($this->deleteClothingSize) {
                $clothingSize->size()->delete();
            }

            //Dispatch a succes message
            $this->dispatch('swal:toast', [
                'background' => 'success',
                'html' => "De kledingmaat <b><i>$nameSize</i></b> is ontkoppelt van <b><i>$nameClothing</i></b>.",
            ]);
            $this->resetValues();
        } catch (Exception $exception) {
            //Dispatch error message
            $this->dispatch('swal:toast', [
                'background' => 'error',
                'html' => "$exception",
            ]);
        }
    }
}
