<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug'];

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'permission_position');
    }
}
