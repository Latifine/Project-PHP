<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonPerTask extends Model
{
    use HasFactory;

    protected $table = 'people_per_task';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function taskPerActivity()
    {
        return $this->belongsTo(TaskPerActivity::class)->withDefault(); //R12
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(); //R7
    }
}
