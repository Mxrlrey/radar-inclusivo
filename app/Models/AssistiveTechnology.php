<?php

namespace App\Models;

use App\Audit\Contracts\Auditable as AuditableContract;
use App\Audit\Formatters\AssistiveTechnologyFormatter;
use App\Enums\ConservationState;
use App\Enums\ResourceStatus;
use App\Models\Traits\Auditable;
use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssistiveTechnology extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable, Reportable;

    /** Estrutura base da model. */
    protected $table = 'assistive_technologies';

    protected $fillable = [
        'name',
        'is_digital',
        'notes',
        'asset_code',
        'quantity',
        'quantity_available',
        'conservation_state',
        'is_loanable',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_digital' => 'boolean',
        'is_loanable' => 'boolean',
        'conservation_state' => ConservationState::class,
        'status' => ResourceStatus::class,
    ];

    protected array $auditExclude = ['quantity_available'];

    /** Configuração de auditoria. */
    public static function auditLabels(): array
    {
        return [
            'name'               => 'Tipo da Tecnologia',
            'is_digital'         => 'Tecnologia Digital',
            'notes'              => 'Descrição',
            'asset_code'         => 'Código de Patrimônio',
            'quantity'           => 'Quantidade Total',
            'conservation_state' => 'Estado de Conservação',
            'status'             => 'Status do Recurso',
            'is_active'          => 'Cadastro Ativo',
            'is_loanable'        => 'Emprestável',
            'deficiencies'       => 'Público-Alvo (Deficiências)',
        ];
    }

    public static function auditFormatter(): string
    {
        return AssistiveTechnologyFormatter::class;
    }

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Tecnologias Assistivas';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'name',
            'is_digital',
            'asset_code',
            'quantity',
            'quantity_available',
            'conservation_state',
            'status',
            'is_active',
            'is_loanable',
            'notes',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id'                  => 'ID',
            'name'                => 'Nome',
            'is_digital'          => 'Digital',
            'asset_code'          => 'Código de Patrimônio',
            'quantity'            => 'Quantidade Total',
            'quantity_available'  => 'Quantidade Disponível',
            'conservation_state'  => 'Estado de Conservação',
            'status'              => 'Status',
            'is_active'           => 'Ativo',
            'is_loanable'         => 'Emprestável',
            'notes'               => 'Observações',
            'created_at'          => 'Data de Cadastro',
        ];
    }

    /** Relacionamentos. */
    public function deficiencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Deficiency::class,
            'assistive_technology_deficiency',
            'assistive_technology_id',
            'deficiency_id'
        );
    }

    public function inspections(): MorphMany
    {
        return $this->morphMany(Inspection::class, 'inspectable')
            ->with('images')
            ->orderByDesc('inspection_date')
            ->orderByDesc('created_at');
    }

    public function latestInspection(): MorphOne
    {
        return $this->morphOne(Inspection::class, 'inspectable')
            ->latestOfMany('inspection_date');
    }

    public function loans(): MorphMany
    {
        return $this->morphMany(Loan::class, 'loanable');
    }

    public function waitlists(): MorphMany
    {
        return $this->morphMany(Waitlist::class, 'waitlistable');
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /** Scopes e filtros. */
    public function scopeFilterName(Builder $query, ?string $name): Builder
    {
        return $name
            ? $query->where('name', 'like', "%{$name}%")
            : $query;
    }

    public function scopeActive(Builder $query, $isActive): Builder
    {
        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', $isActive == '1');
        }
        return $query;
    }

    public function scopeAvailable(Builder $query, $available): Builder
    {
        if (!is_null($available) && $available !== '') {
            $available == '1'
                ? $query->where(function (Builder $q) {
                    $q->where('is_digital', true)
                        ->orWhere('quantity_available', '>', 0);
                })
                : $query->where('is_digital', false)
                    ->where(function (Builder $q) {
                        $q->whereNull('quantity_available')
                            ->orWhere('quantity_available', '<=', 0);
                    });
        }
        return $query;
    }

    public function scopeDigital(Builder $query, $isDigital): Builder
    {
        if (!is_null($isDigital) && $isDigital !== '') {
            $query->where('is_digital', $isDigital == '1');
        }
        return $query;
    }
}
