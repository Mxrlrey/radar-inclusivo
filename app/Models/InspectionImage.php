<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class InspectionImage extends Model
{
    use HasFactory;

    /** Estrutura base da model. */
    protected $fillable = [
        'inspection_id',
        'path',
        'original_name',
        'mime_type',
        'size'
    ];

    /** Eventos e regras auxiliares da model. */
    protected static function booted()
    {
        static::deleted(function ($image) {
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        });
    }

    /** Relacionamentos. */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class, 'inspection_id');
    }
}
