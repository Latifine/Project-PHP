<?php

namespace App\Livewire\Forms;

use App\Models\Task;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TaskForm extends Form
{
    public $id = null;
    #[Validate('required', as: 'name of the task')]
    public $task = null;

    public function read($task)
    {
        $this->id = $task->id;
        $this->task = $task->task;
    }

    public function create()
    {
        $this->validate();
        Task::create([
            'task' => $this->task,
//            'created_at' => now(),
//            'updated_at' => now()
        ]);
    }

    public function update(Task $task)
    {
        $this->validate();
        $task->update([
            'task' => $this->task,
        ]);
    }

    public function delete(Task $task)
    {
        $task->delete();
    }
}
