<?php

namespace App\Livewire;

use App\Livewire\Forms\PeoplePerTaskForm;
use App\Livewire\Forms\TaskForm;
use App\Livewire\Forms\TaskPerActivityForm;
use App\Models\PersonPerTask;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskPerActivity;
use App\Models\TrainingMatch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class Beurtrol extends Component
{
    public bool $isnotintask;

    public TaskPerActivity $currentTask;

    public $beurtrol;
    public TaskForm $taskForm;
    public PeoplePerTaskForm $form;
    public TaskPerActivityForm $beurtrolForm;
    public $training = false;
    public $showModal = false;
    public $showTaakModal = false;
    public $showBeurtrolModal = false;

    public User $varuser;

    #[Layout('layouts.app', [
        'title' => "Beurtrol",
        'subtitle' => 'Beurtrol',
        'description' => 'Hier kan je als ouder beurtrollen zien en ook aan aansluiten.',
        'developer' => 'Hamza Qurayshi'
    ])]

    public function render()
    {

        $users = User::orderby('id')->get();
        $user = Auth::user();
        $peoplepertasks = PersonPerTask::whereHas('taskPerActivity', function ($query1) {
            $query1->whereHas('activity', function ($query) {
                $query->where('date', '>=', Carbon::today());
            });
        })->orderBy('user_id')->get();
        $activiteiten = TrainingMatch::orderby('id')->get();
        $rollen = Task::orderby('id')->get();
        $beurtrollen  = TaskPerActivity::whereHas('activity', function ($query) {
            $query->where('date', '>=', Carbon::today());
        })->orderBy('id')->get();

        // Check if the user's role is "Admin"
        $isAdmin = false;
        if ($user) {
            $isAdmin = $user->role()->where('role', 'Admin')->exists();
        }

        return view('livewire.beurtrol',compact('peoplepertasks','beurtrollen','activiteiten','rollen', 'isAdmin','users'));
    }

    public function newPersonPerActivity(TaskPerActivity $taskPerActivity)
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->currentTask = $taskPerActivity;
        $this->showModal = true;
    }
    public function createPersonPerActivity()
    {
        $user = Auth::User();
        $this->form->user_id = $user->id;
        $this->form->task_per_activity_id = $this->currentTask->id;
        $this->form->is_assigned = false;
        $this->form->create();
        $this->showModal=false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "je aanvraag is verzonden",
            'icon' => 'success',
        ]);

    }
    #[On('delete-aanvraag')]
    public function deletePersonPerTask($id)
    {
        $this->form->delete($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "Je bent niet meer beschikbaar",
            'icon' => 'success',
        ]);
    }

    public function newRol()
    {
        $this->taskForm->reset();
        $this->resetErrorBag();
        $this->showTaakModal = true;
    }

    public function newBeurtrol()
    {
        $this->beurtrolForm->id = null;
        $this->beurtrolForm->reset();
        $this->resetErrorBag();
        $this->showBeurtrolModal = true;
    }

    public function createRol()
    {
        $this->taskForm->create();
        $this->showTaakModal=false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De taak is aangemaakt",
            'icon' => 'success',
        ]);

    }

    public function createBeurtrol()
    {
        $this->beurtrolForm->create();
        $this->showBeurtrolModal=false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De beurtrol is aangemaakt",
            'icon' => 'success',
        ]);
    }

    #[On('delete-taak')]
    public function deleteTaskPerActivity($id)
    {
        $this->form->reset();
        $this->resetErrorBag();
        $this->beurtrolForm->delete($id);
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De taak is verwijderd",
            'icon' => 'success',
        ]);
    }

    #[On('update-status')]
    public function updatePersonPerTask($id)
    {
        $perTask= PersonPerTask::findOrFail($id);
        $beurtrollen = TaskPerActivity::orderby('id')->get();

        foreach ($beurtrollen as $beurt)
            if ($beurt->id == $perTask->task_per_activity_id)
                $this->beurtrol = $beurt;

        if ($perTask->is_assigned)
        {
            $perTask->is_assigned = false;
            $this->beurtrol->quantity += 1;

        }else
        {
            $perTask->is_assigned = true;
            $this->beurtrol->quantity += -1;

        }
        $this->beurtrolForm->fill($this->beurtrol);
        $this->form->fill($perTask);
        $this->form->update($perTask);
        $this->beurtrolForm->update($this->beurtrol);
        $this->form->sendMail();
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De beurtrol is bijgewerkt",
            'icon' => 'success',
        ]);
    }

    public function editBeurtrol(TaskPerActivity $taskPerActivity)
    {
        $this->resetErrorBag();
        $this->beurtrolForm->fill($taskPerActivity);
        $this->showBeurtrolModal = true;
    }
    public function updateBeurtrol(TaskPerActivity $taskPerActivity)
    {
        $this->beurtrolForm->update($taskPerActivity);
        $this->showBeurtrolModal = false;
        $this->dispatch('swal:toast', [
            'background' => 'success',
            'html' => "De beurtrol is aangepast",
            'icon' => 'success',
        ]);
    }
}
