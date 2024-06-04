<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentPerChild extends Model
{
    use HasFactory;

    protected $table = 'parents_per_child';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function child()
    {
        return $this->belongsTo(User::class, 'user_child_id')->withDefault();
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_parent_id')->withDefault();
    }
}
