<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    /** Estrutura base da model. */
    protected $fillable = ['file_name', 'file_path', 'size', 'status', 'user_id'];

    /** Relacionamentos. */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Scopes e filtros. */
    public function scopeFilterName($query, $name)
    {
        if ($name) {
            return $query->where('file_name', 'like', "%{$name}%");
        }
    }

    public function scopeByType($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
    }

    public function scopeByUser($query, $userId)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
    }
}
