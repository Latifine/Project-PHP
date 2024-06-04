<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothingPerPlayer extends Model
{
    use HasFactory;

    protected $table = 'clothing_per_player';

    protected $guarded = ['id','created_at', 'updated_at'];


    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(); //R2
    }

    public function playerName()
    {
        return $this->user->name;
    }

    public function clothingSize()
    {
        return $this->belongsTo(ClothingSize::class)->withDefault(); //R19
    }
}
