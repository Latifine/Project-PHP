<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clothing extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function clothingSizes()
    {
        return $this->hasMany(ClothingSize::class); //R18
    }
    public function scopeSearchClothing($query, $search = '%')
    {
        return $query->where('clothing', 'like', "%{$search}%");
    }
}
