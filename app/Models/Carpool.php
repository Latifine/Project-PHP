<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpool extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(); //R10
    }
    public function training_matches()
    {
        return $this->belongsTo(TrainingMatch::class)->withDefault();
    }

    public function carpoolPeople()
    {
        return $this->hasMany(CarpoolPerson::class); //R16
    }
}
