<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Carpool;

class CarpoolForm extends Form
{
    public $id = null;
    public $userid = null;
    #[Validate('required', as: 'training/match')]
    public $date = null;
    #[Validate('required|numeric|min:1', as: 'plaatsen')]
    public $quantity = null;
    #[Validate('required', as: 'uur')]
    public $hour = null;
    #[Validate('required', as: 'adres')]
    public $address = null;

    public function read($carpool)
    {
        $this->id = $carpool->id;
        $this->userid = $carpool->user_id;
        $this->date = $carpool->date;
        $this->quantity = $carpool->quantity;
        $this->hour = $carpool->hour;
        $this->address = $carpool->address;
    }

    public function create()
    {
        $this->validate();
        $user = Auth::User();
        Carpool::create([
            'user_id' => $user->id,
            'training_matches_id' => $this->date,
            'quantity' => $this->quantity,
            'hour' => $this->hour,
            'address' => $this->address
        ]);
    }

    public function update(Carpool $carpool)
    {
        $this->validate();
        $carpool->update([
            'training_matches_id' => $this->date,
            'quantity' => $this->quantity,
            'hour' => $this->hour,
            'address' => $this->address
        ]);
    }

    public function delete($id)
    {
        $carpool = Carpool::findOrFail($id);
        $carpool->delete($id);
    }

}
