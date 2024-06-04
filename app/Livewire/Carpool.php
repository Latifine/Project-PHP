<?php

namespace App\Livewire;


use App\Livewire\Forms\CarpoolForm;
use App\Livewire\Forms\CarpoolPersonForm;
use App\Models\CarpoolPerson;
use App\Models\TrainingMatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Carpool extends Component
{

    public $showModal = false;
    public $showModalCarpoolPerson = false;
    public CarpoolForm $form;
    public CarpoolPersonForm $formCarpoolPerson;
    public \App\Models\Carpool $currentCarpool;
    public $vartraining;
    public int $currentquantity;
    #[Layout('layouts.app', [
        'title' => "Carpool",
        'subtitle' => 'Carpool',
        'description' => 'Hier kan je alle actieve carpools zien en ook zelf een carpool aanbieden.',
        'developer' => 'Hamza Qurayshi'
    ])]

    public function newCarpoolPerson(\App\Models\Carpool $carpool)
    {
        $this->currentCarpool = $carpool;
        $this->formCarpoolPerson->reset();
        $this->resetErrorBag();
        $this->showModalCarpoolPerson = true;
    }

    public function newCarpool()
    {

        $this->form->reset();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function createCarpool()
    {
        $this->form->create();
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De carpool is aangemaakt",
            'icon' => 'success',
        ]);
    }

    public function createCarpoolPerson()
    {
        $this->formCarpoolPerson->create($this->currentCarpool);
        $this->showModalCarpoolPerson = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "U bent toegevoegd aan de carpool",
            'icon' => 'success',
        ]);

    }

    public function editCarpool(\App\Models\Carpool $carpool)
    {
        $this->resetErrorBag();
        $this->form->fill($carpool);
        $this->showModal = true;
    }
    public function editCarpoolPerson(CarpoolPerson $carpoolPerson)
    {
        $carpools = \App\Models\Carpool::orderby('id')->get();
        foreach ($carpools as $carpoo)
            if ($carpoo->id == $carpoolPerson->carpool_id)
                $carpool = $carpoo;
                $this->currentCarpool = $carpool;
        $this->resetErrorBag();
        $this->formCarpoolPerson->fill($carpoolPerson);
        $this->currentquantity = $carpoolPerson->quantity;
        $this->showModalCarpoolPerson = true;
    }
    public function updateCarpool(\App\Models\Carpool $record)
    {
        $this->form->update($record);
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De carpool is bijgewerkt",
            'icon' => 'success',
        ]);
    }
    public function updateCarpoolPerson(CarpoolPerson $record)
    {
        $this->formCarpoolPerson->update($record, $this->currentCarpool, $this->currentquantity);
        $this->showModalCarpoolPerson = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Uw carpool plaatsen zijn bijgewerkt",
            'icon' => 'success',
        ]);
    }
    #[On('delete-carpool')]
    public function deleteCarpool($id)
    {
        $this->form->delete($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De carpool is verwijderd",
            'icon' => 'success',
        ]);
    }
    #[On('delete-plaatsen')]
    public function deleteCarpoolPerson($id)
    {
        $this->formCarpoolPerson->delete($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Uw carpool plaatsen zijn verwijderd",
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $carpools = \App\Models\Carpool::whereHas('training_matches', function ($query) {
            $query->where('date', '>', now());
        })->orderBy('id')->get();
        $carpoolpeople = CarpoolPerson::orderby('id')->get();
        $training = TrainingMatch::where('date', '>', now())->orderby('id')->get();
        $currentUser = Auth::User();
        $users = User::orderby('id')->get();
        return view('livewire.carpool',compact('training','carpools','users','currentUser','carpoolpeople'));
    }

}
