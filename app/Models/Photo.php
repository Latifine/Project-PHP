<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Photo extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['image'];

    public function album()
    {
        return $this->belongsTo(Album::class)->withDefault(); //R17
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $imagePath = 'photos/' . $attributes['album_id'] . '/' . $attributes['name'] . '.jpg';
                return (Storage::disk('public')->exists($imagePath))
                    ? Storage::url($imagePath)
                    : null;
            },
        );
    }

}
