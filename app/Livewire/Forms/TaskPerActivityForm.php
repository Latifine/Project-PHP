<?php

namespace App\Livewire\Forms;

use App\Models\TaskPerActivity;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TaskPerActivityForm extends Form
{
    public $id = null;
    #[Validate('required', as: 'Activiteit')]
    public $activity_id = null;
    #[Validate('required', as: 'Rol')]
    public $task_id = null;
    #[Validate('required|numeric|min:0', as: 'hoeveelheid')]
    public $quantity = null;

    public function read(TaskPerActivity $taskperactivity)
    {
        $this->id = $taskperactivity->id;
        $this->activity_id = $taskperactivity->activity_id;
        $this->task_id = $taskperactivity->task_id;
        $this->quantity = $taskperactivity->quantity;
    }

    public function create()
    {
        $this->validate();
        TaskPerActivity::create([
            'activity_id'=>$this->activity_id,
            'task_id'=>$this->task_id,
            'quantity'=>$this->quantity
        ]);
    }

    public function update(TaskPerActivity $taskPerActivity)
    {
        $this->validate();
        $taskPerActivity->update([
            'activity_id'=>$this->activity_id,
            'task_id'=>$this->task_id,
            'quantity'=>$this->quantity
        ]);
    }

    public function delete($id)
    {
        $taskPerActivity= TaskPerActivity::findOrFail($id);
        $taskPerActivity->delete();
    }
}
