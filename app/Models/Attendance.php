<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function trainingMatch()
    {
        return $this->belongsTo(TrainingMatch::class, 'activity_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(); //R8
    }

}
