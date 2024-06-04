<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarpoolPerson extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(); //R15
    }

    public function carpool()
    {
        return $this->belongsTo(Carpool::class)->withDefault(); //R16
    }
}
