<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothingSize extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function clothingPerPlayer()
    {
        return $this->hasMany(ClothingPerPlayer::class); //R19
    }

    public function clothing()
    {
        return $this->belongsTo(Clothing::class)->withDefault(); //R18
    }

    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault(); //R20
    }

    public function scopeSearchClothingOrSize($query, $search = '%')
    {
        return $query->whereHas('clothing', function ($query) use ($search) {
            $query->where('clothing', 'like', "%{$search}%");
        })->orWhereHas('size', function ($query) use ($search) {
            $query->where('size', 'like', "%{$search}%");
        });
    }
}
