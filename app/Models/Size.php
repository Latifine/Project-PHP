<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function clothingSizes()
    {
        return $this->hasMany(ClothingSize::class); //R20
    }
    public function scopeSearchSize($query, $search = '%')
    {
        return $query->where('size', 'like', "%{$search}%");
    }
}
