<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /** Estrutura base da model. */
    protected $fillable = ['name', 'slug'];

    /** Relacionamentos. */
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'permission_position');
    }
}
