<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskPerActivity extends Model
{
    use HasFactory;

    protected $table = 'tasks_per_activity';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function task()
    {
        return $this->belongsTo(Task::class)->withDefault(); //R13
    }

    public function activity()
    {
        return $this->belongsTo(TrainingMatch::class)->withDefault(); //R11
    }

    public function peoplePerTask()
    {
        return $this->hasMany(PersonPerTask::class); //R12
    }
}
