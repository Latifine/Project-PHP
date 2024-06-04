<?php

namespace App\Livewire\Forms;

use App\Models\Carpool;
use App\Models\CarpoolPerson;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CarpoolPersonForm extends Form
{

    public $id = null;
    #[Validate('required|exists:users,id', as: 'user')]
    public $user_id = null;
    #[Validate('required|exists:carpools,id', as: 'carpool')]
    public $carpool_id = null;
    public $currentQuantity = null;
    #[Validate('required|numeric|min:1', as: 'plaatsen')]
    public $quantity = null;


    public function read($carpoolPerson){
        $this->id = $carpoolPerson->id;
        $this->user_id = $carpoolPerson->user_id;
        $this->carpool_id = $carpoolPerson->carpool_id;
        $this->quantity = $carpoolPerson->quantity;
    }

    public function create(Carpool $carpool)
    {
        if ($this->quantity == null)
        {
            $this->quantity = 1;
        }
        $this->user_id = auth()->user()->id;

        $this->carpool_id = $carpool->id;
        $this->validate([
            'carpool_id' => [
                'required',
                'exists:carpools,id',
                Rule::exists('carpools', 'id')->where(function ($query) {
                    $query->where('id', $this->carpool_id)
                        ->where('quantity', '>=', $this->quantity);
                }),
            ],
        ]);
        $this->validate();
        $carpool->update([
            'date' => $carpool->date,
            'quantity' => $carpool->quantity - $this->quantity,
            'hour' => $carpool->hour,
            'address' => $carpool->address

        ]);
        CarpoolPerson::create([
            'user_id' => $this->user_id,
            'carpool_id' => $this->carpool_id,
            'quantity' => $this->quantity
        ]);
    }

    public function update(CarpoolPerson $carpoolPerson, Carpool $carpool, int $currentQuantity)
    {
        $this->carpool_id = $carpool->id;
        $this->currentQuantity = $this->quantity - $currentQuantity;
        $diff_quantity =$this->quantity - $currentQuantity;
        $this->validate([
            'carpool_id' => [
                'required',
                'exists:carpools,id',
                Rule::exists('carpools', 'id')->where(function ($query) {
                    $query->where('id', $this->carpool_id)
                        ->where('quantity', '>=', $this->currentQuantity);
                }),
            ],
        ]);
        $this->validate();
        $carpool->update([
            'date' => $carpool->date,
            'quantity' => $carpool->quantity - $diff_quantity,
            'hour' => $carpool->hour,
            'address' => $carpool->address

        ]);
        $carpoolPerson->update([
            'user_id' => $this->user_id,
            'carpool_id' => $this->carpool_id,
            'quantity' => $this->quantity
        ]);
    }

    public function delete($id)
    {
        $carpoolPerson = CarpoolPerson::findOrFail($id);
        $carpools = Carpool::orderby('id')->get();
        foreach ($carpools as $carpoo)
            if ($carpoo->id == $carpoolPerson->carpool_id)
                $carpool = $carpoo;
        $carpool->update([
            'date' => $carpool->date,
            'quantity' => $carpool->quantity + $carpoolPerson->quantity,
            'hour' => $carpool->hour,
            'address' => $carpool->address

        ]);
        $carpoolPerson->delete();
    }



}
