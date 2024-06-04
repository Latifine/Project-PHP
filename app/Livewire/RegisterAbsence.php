<?php

namespace App\Livewire;

use App\Livewire\Forms\AttendanceForm;
use App\Models\Attendance;
use App\Models\ParentPerChild;
use App\Models\TrainingMatch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class RegisterAbsence extends Component
{
    public $showModal = false;
    public $selectedType = 'training';
    public AttendanceForm $form;

    #[Layout('layouts.app', ['title' => 'Afwezigheid', 'description' => 'Afwezigheden',
        'developer' => 'Jorrit Leysen'])]
    public function render()
    {
        $query = TrainingMatch::where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc');

        if ($this->selectedType === 'training') {
            $query->where('is_match', 0);
        } else {
            $query->where('is_match', 1);
        }

        // Get the filtered match trainings
        $filteredMatchTrainings = $query->get();

        if (Auth::user()->role_id === 1) {
            $parentChildren = ParentPerChild::pluck('user_child_id')
                ->toArray();
            $absences = Attendance::select('attendances.*')
                ->join('training_matches', 'attendances.activity_id', '=', 'training_matches.id')
                ->orderBy('training_matches.date', 'asc')
                ->with('trainingMatch')
                ->with('user')
                ->get();

        }
        else {
            $parentChildren = ParentPerChild::where('user_parent_id', Auth::user()->id)->pluck('user_child_id')
                ->toArray();
            $absences = Attendance::select('attendances.*')
                ->join('training_matches', 'attendances.activity_id', '=', 'training_matches.id')
                ->whereIn('attendances.user_id', $parentChildren)
                ->orderBy('training_matches.date', 'asc')
                ->with('trainingMatch')
                ->with('user')
                ->get();

        }
        $children = User::whereIn('id', $parentChildren)->get();
        return view('livewire.register-absence', compact('absences', 'filteredMatchTrainings', 'children'));
    }

    public function updateFilteredMatchTrainings()
    {
        // Apply filters based on selected type
        $query = TrainingMatch::where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc');

        if ($this->selectedType === 'training') {
            $query->where('is_match', 0);
        } else {
            $query->where('is_match', 1);
        }

        // Get the filtered match trainings
        $this->filteredMatchTrainings = $query->get();
    }

    public function newAbsence()
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function createAbsence()
    {
        $this->form->create();
        $this->form->reset();
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De afwezigheid is geregistreerd.",
        ]);
    }

    public function editAbsence(Attendance $absence)
    {
        $this->resetErrorBag();
        $this->form->reset();
        $this->form->fill($absence);
        $this->showModal = true;
    }

    public function updateAbsence(Attendance $absence)
    {
        $this->form->update($absence);
        $this->showModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De afwezigheid is bijgewerkt.",
        ]);
    }
    #[On('delete-absence')]
    public function deleteAbsence($id)
    {
        $this->form->delete($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De afwezigheid is verwijderd.",
        ]);
    }
}
