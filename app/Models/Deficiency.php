<?php

namespace App\Models;

use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deficiency extends Model
{
    use HasFactory, SoftDeletes, Reportable;

    /** Estrutura base da model. */
    protected $table = 'deficiencies';

    protected $fillable = [
        'name',
        'cid_code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Deficiências';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'name',
            'cid_code',
            'description',
            'is_active',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id'          => 'ID',
            'name'        => 'Nome da Deficiência',
            'cid_code'    => 'Código CID',
            'description' => 'Descrição',
            'is_active'   => 'Ativa',
            'created_at'  => 'Data de Cadastro',
        ];
    }

    /** Relacionamentos. */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'students_deficiencies')
            ->withPivot([
                'severity',
                'uses_support_resources',
                'notes'
            ])
            ->withTimestamps();
    }

    public function barriers(): BelongsToMany
    {
        return $this->belongsToMany(
            Barrier::class,
            'barrier_deficiency',
            'deficiency_id',
            'barrier_id'
        )->withTimestamps();
    }

    public function assistiveTechnologies(): BelongsToMany
    {
        return $this->belongsToMany(
            AssistiveTechnology::class,
            'assistive_technology_deficiency',
            'deficiency_id',
            'assistive_technology_id'
        );
    }

    public function accessibleEducationalMaterials(): BelongsToMany
    {
        return $this->belongsToMany(
            AccessibleEducationalMaterial::class,
            'accessible_educational_material_deficiency',
            'deficiency_id',
            'accessible_educational_material_id'
        );
    }

    /** Scopes e filtros. */
    public function scopeName(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('name', 'like', "{$term}%") : $query;
    }

    public function scopeCid(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('cid_code', 'like', "{$term}%") : $query;
    }

    public function scopeActive(Builder $query, $isActive): Builder
    {
        if ($isActive === null || $isActive === '') {
            return $query;
        }
        return $query->where('is_active', (bool) $isActive);
    }
}
