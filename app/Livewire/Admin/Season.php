<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\SeasonForm;
use App\Models\Season as SeasonModel;

class Season extends Component
{
    public SeasonForm $form;

    public $search;
    public $showModal = false;
    public $seasons;
    public $spelerregistratie = false;

    #[Layout('layouts.app', ['title' => 'Seizoen aanmaken', 'description' => 'Pagina voor het aanmaken van een nieuw seizoen',
        'developer' => 'Gilles Bosmans'])]

    // function to update the spelerregistratie
    public function updateSpeleRegistratie()
    {
        $this->spelerregistratie = !$this->spelerregistratie;
    }

    // Add new season
    public function newSeason()
    {
        $this->form->reset();
        $this->resetErrorBag();
        //open model
        $this->showModal = true;
    }

    // Create new season
    public function createSeason()
    {
        $newSeason = $this->form->create();
        // If no new season is created, return
        if (!$newSeason) {
            return;
        }
        $this->form->errorMessage = [];
        // Create registrations for all users
        $this->spelerregistratie ? $this->form->createRegistrations($newSeason) : null;
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Nieuw seizoen toegevoegd.",
            'icon' => 'success',
        ]);
    }


    // delete a season
    #[On('delete-season')]
    public function deleteSeason($id)
    {
        try {
            $this->form->delete($id);

            $this->dispatch('swal:toast', [
                'background' => 'success',
                'html' => 'Seizoen succesvol verwijderd',
                'icon' => 'success',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                $this->dispatch('swal:toast', [
                    'icon' => "error",
                    'title' => "Oops...",
                    'text' => "Kan niet worden verwijderd, mogelijk heeft u nog spelers geregistreerd voor dit seizoen.",
                ]);
            }
        }
    }

    public function render()
    {
        $this->seasons = SeasonModel::orderBy('date_start')->get();
        return view('livewire.admin.season');
    }
}
