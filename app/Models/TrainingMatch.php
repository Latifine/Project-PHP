<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingMatch extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function tasksPerActivity()
    {
        return $this->hasMany(TaskPerActivity::class); //R11
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class); //R9
    }
    public function carpool()
    {
        return $this->hasMany(Carpool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(); //R14
    }
}
