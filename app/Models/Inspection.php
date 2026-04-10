<?php

namespace App\Models;

use App\Enums\BarrierStatus;
use App\Enums\ConservationState;
use App\Enums\InspectionType;
use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Inspection extends Model
{
    use HasFactory, Reportable;

    protected $fillable = [
        'inspectable_id',
        'inspectable_type',
        'state',
        'status',
        'inspection_date',
        'description',
        'type',
        'user_id'
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'state' => ConservationState::class,
        'status' => BarrierStatus::class,
        'type' => InspectionType::class,
    ];

    public function getInspectableNameAttribute(): ?string
    {
        return $this->inspectable?->name ?? null;
    }

    public static function getReportLabel(): string
    {
        return 'Inspeções';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'state',
            'status',
            'inspectable_name',
            'type',
            'inspection_date',
            'description',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id'              => 'ID',
            'state'           => 'Estado de Conservação',
            'status'          => 'Status da Barreira',
            'inspectable_name' => 'Item Inspecionado',
            'type'            => 'Tipo de Inspeção',
            'inspection_date' => 'Data da Inspeção',
            'description'     => 'Descrição',
            'created_at'      => 'Data de Cadastro',
        ];
    }

    public function inspectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function images(): HasMany
    {
        return $this->hasMany(InspectionImage::class, 'inspection_id');
    }

}
