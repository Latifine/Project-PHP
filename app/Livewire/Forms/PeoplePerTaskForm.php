<?php

namespace App\Livewire\Forms;

use App\Mail\BeurtrolMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use App\Models\PersonPerTask;
use App\Models\TaskPerActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Form;


class PeoplePerTaskForm extends Form
{

    public $id = null;
    #[Validate('required', as: 'gebruiker')]
    public $user_id = null;
    #[Validate('required', as:'rol per activiteit')]
    public $task_per_activity_id = null;
    public $reason_exceptional_circumstance = null;
    #[Validate('required', as: 'is actief')]
    public $is_assigned = null;

    public $currenttask;


    public function sendMail()
    {
        $task = null;
        $user = null;
        $tasks = TaskPerActivity::orderby('id')->get();
        $users = User::orderby('id')->get();
        foreach ($users as $use)
            if ($use->id == $this->user_id)
                $user = $use;
        foreach ($tasks as $tas)
            if($tas->id == $this->task_per_activity_id)
                $task = $tas;
        $this->validate();
        $template = new BeurtrolMail([
            'fromName' => 'Kvv Rauw Sport Mol',
            'fromEmail' => 'admin@project-kvvrauw.be',
            'subject' => 'Kvv Rauw Sport Mol - Beurtrol Status',
            'name' => $user->name,
            'email' => $user->email,
            'beurtrol' => $task,
        ]);
        $to = new Address($user->email, $user->name);
        Mail::to($to)
            ->send($template);
        $this->reset();
    }

    public function read(PersonPerTask $perTask)
    {
        $this->id = $perTask->id;
        $this->user_id = $perTask->user_id;
        $this->task_per_activity_id = $perTask->task_per__activity_id;
        $this->reason_exceptional_circumstance = $perTask->reason_exceptional_circumstance;
        $this->is_assigned = $perTask->is_assigned;
    }

    public function create()
    {
        $this->validate();
//        $taskper = TaskPerActivity::orderby('id')->get();
//        foreach ($taskper as $item)
//        {
//            if ($item->id == $this->task_per_activity_id)
//            {
//                $quantity = $item->quantity - 1;
//                $current_task_per_activity = $item;
//                $current_task_per_activity->update([
//                    'quantity' => $quantity
//                ]);
//            }
//
//        }
        PersonPerTask::create([
            'user_id'=> $this->user_id,
            'task_per_activity_id'=>$this->task_per_activity_id,
            'reason_exceptional_circumstance'=>$this->reason_exceptional_circumstance,
            'is_assigned'=>$this->is_assigned,
        ]);
    }
    public function update(PersonPerTask $perTask)
    {
        $this->validate();
        $perTask->update([
            'user_id'=> $this->user_id,
            'task_per_activity_id'=>$this->task_per_activity_id,
            'reason_exceptional_circumstance'=>$this->reason_exceptional_circumstance,
            'is_assigned'=>$this->is_assigned,
        ]);
    }
    public function delete($id)
    {
//        $taskper = TaskPerActivity::orderby('id')->get();
//        if ($perTask->is_assigned)
//            foreach ($taskper as $task)
//                if ($perTask->task_per_activity_id == $task->id)
//                    $this->currenttask = $task;
//                    $this->currenttask->quantity += 1 ;
//                    $this->currenttask->update([
//                        'quantity'=>$task->quantity
//                    ]);
//
//        $perTask->delete();
        $taskper = TaskPerActivity::orderby('id')->get();
        $perTask = PersonPerTask::findOrFail($id);
        if ($perTask->is_assigned)
        {
            foreach ($taskper as $task)
            {
                if ($perTask->task_per_activity_id == $task->id)
                {
                    $this->currenttask = $task;
                    $this->currenttask->quantity += 1 ;
                    $this->currenttask->update([
                        'quantity'=>$task->quantity
                    ]);
                }
            }

        }

        $perTask->delete();
    }
}
