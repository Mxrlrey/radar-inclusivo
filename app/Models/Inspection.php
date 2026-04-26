<?php

namespace App\Models;

use App\Enums\BarrierStatus;
use App\Enums\ConservationState;
use App\Enums\InspectionType;
use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Inspection extends Model
{
    use HasFactory, Reportable;

    /** Estrutura base da model. */
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

    /** Atributos derivados e regras auxiliares. */
    public function getInspectableNameAttribute(): ?string
    {
        return $this->inspectable?->name ?? null;
    }

    /** Configuração do builder de relatórios. */
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

    /** Contrato de relações especiais para relatórios. */
    public static function getReportRelations(): array
    {
        return [
            'barrier' => [
                'relation' => 'inspectable',
                'type_column' => 'inspectable_type',
                'target' => Barrier::class,
            ],
            'assistiveTechnology' => [
                'relation' => 'inspectable',
                'type_column' => 'inspectable_type',
                'target' => AssistiveTechnology::class,
            ],
            'accessibleEducationalMaterial' => [
                'relation' => 'inspectable',
                'type_column' => 'inspectable_type',
                'target' => AccessibleEducationalMaterial::class,
            ],
        ];
    }

    /** Relacionamentos. */
    public function inspectable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function barrier(): BelongsTo
    {
        return $this->belongsTo(Barrier::class, 'inspectable_id');
    }

    public function assistiveTechnology(): BelongsTo
    {
        return $this->belongsTo(AssistiveTechnology::class, 'inspectable_id')->withTrashed();
    }

    public function accessibleEducationalMaterial(): BelongsTo
    {
        return $this->belongsTo(AccessibleEducationalMaterial::class, 'inspectable_id')->withTrashed();
    }

    public function images(): HasMany
    {
        return $this->hasMany(InspectionImage::class, 'inspection_id');
    }
}
