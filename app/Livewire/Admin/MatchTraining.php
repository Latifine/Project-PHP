<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use App\Livewire\Forms\MatchTrainingForm;
use App\Models\TrainingMatch;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class MatchTraining extends Component
{
    public $showMatchModal = false;
    public $showTrainingModal = false;
    public $showAllTrainingModal = false;
    public $orderBy = 'date';
    public $orderAsc = true;
    public $upcoming = false;
    public MatchTrainingForm $form;

    #[Layout('layouts.app', ['title' => 'Wedstrijden & Trainingen', 'description' => 'Matches & Trainings',
        'developer' => 'Jorrit Leysen'])]
    public function render()
    {
        if (!$this->upcoming)
            $query = TrainingMatch::where('date', '>=', Carbon::now())
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->get();
        else
            $query = TrainingMatch::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->get();

        $matchTrainings = $query;
        return view('livewire.admin.training-match', compact('matchTrainings'));
    }

    public function newMatch()
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showMatchModal = true;
    }

    public function newTraining()
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showTrainingModal = true;
    }

    public function allTrainings()
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showAllTrainingModal = true;
    }

    public function createMatch()
    {
        $this->form->is_match = true;
        $this->form->create();
        $this->showMatchModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De wedstrijd op <b><i>{$this->form->date}</i></b> is toegevoegd.",
            'icon' => 'success'
        ]);
    }
    public function createTraining()
    {
        $this->form->create();
        $this->showTrainingModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De training op <b><i>{$this->form->date}</i></b> is toegevoegd.",
            'icon' => 'success'
        ]);
    }

    public function createAllTrainings()
    {
        $this->form->createAllTrainings();
        $this->showAllTrainingModal = false;
        $this->form->reset();
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De trainingen tot en met <b><i>{$this->form->endDate}</i></b> zijn toegevoegd.",
            'icon' => 'success'
        ]);
    }

    public function editMatch(TrainingMatch $matchTraining)
    {
        $this->resetErrorBag();
        $this->form->reset();
        $this->form->fill($matchTraining);
        $this->showMatchModal = true;
    }

    public function updateMatch(TrainingMatch $matchTraining)
    {
        $this->form->update($matchTraining);
        $this->showMatchModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De wedstrijd op <b><i>{$matchTraining->date}</i></b> is bijgewerkt.",
            'icon' => 'success'
        ]);
    }

    public function editTraining(TrainingMatch $matchTraining)
    {
        $this->resetErrorBag();
        $this->form->fill($matchTraining);
        $this->showTrainingModal = true;
    }

    public function updateTraining(TrainingMatch $matchTraining)
    {
        $this->form->update($matchTraining);
        $this->showTrainingModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De training op <b><i>{$matchTraining->date}</i></b> is bijgewerkt.",
            'icon' => 'success'
        ]);
    }
    #[On('delete-matchTraining')]
    public function deleteMatchTraining($id)
    {
        $this->form->delete($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De wedstrijd of training is verwijderd.",
            'icon' => 'success',
        ]);
    }

    // resort the matches & trainings by the given column
    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }
}
