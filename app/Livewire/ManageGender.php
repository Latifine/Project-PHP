<?php

namespace App\Livewire;

use App\Models\Gender;
use Carbon\Factory;
use Illuminate\Foundation\Application;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Contracts\View\View;

//Jorrit Janssens - Shelved
class ManageGender extends Component
{

    public $orderById = 'id';
    #[Validate('required|min:1|unique:gender', as: 'name for this gender')]
    public $newGender;
    public $orderByName = 'gender';
    public $orderIdAsc = true;
    public $orderNameAsc = true;

    public function render(): View|Factory|Application
    {
        $allGenders = (new Gender)
            ->orderBy($this->orderById, $this->orderIdAsc  ? 'asc' : 'desc')
            ->orderBy($this->orderByName, $this->orderNameAsc ? 'asc' : 'desc')
            ->get();
        return view('livewire.manage-gender', compact('allGenders'));
    }

    // create a new programme
    public function create(): void
    {
        // validate the new gender
        $this->validateOnly('newGender');
        // create the gender
        $gender = Gender::create([
            'gender' => trim($this->newGender),
        ]);
        // reset $newGender
        $this->resetValues();
        // toast
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "The Gender <b><i>$gender->gender</i></b> has been added",
        ]);
    }

    // reset all the values and error messages
    public function resetValues(): void
    {
        $this->reset('newGender','editGender');
        $this->resetErrorBag();
    }

    // delete a gender
    #[On('delete-programme')]
    public function delete($id):void
    {
        $gender = Gender::findOrFail($id);
        $gender->delete();
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "The programme <b><i>$gender->gender</i></b> has been deleted",
        ]);
    }
}
