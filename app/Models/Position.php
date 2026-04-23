<?php

namespace App\Models;

use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes, Reportable;

    /** Estrutura base da model. */
    protected $table = 'positions';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Cargos e Funções';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nome do Cargo',
            'description' => 'Descrição',
            'is_active' => 'Ativo',
            'created_at' => 'Data de Cadastro',
        ];
    }

    /** Relacionamentos. */
    public function professionals(): HasMany
    {
        return $this->hasMany(Professional::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_position');
    }

    /** Scopes e filtros. */
    public function scopeName(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('name', 'like', "{$term}%") : $query;
    }

    public function scopeDescription(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('description', 'like', "%{$term}%") : $query;
    }

    public function scopeActive(Builder $query, $isActive): Builder
    {
        if (!is_null($isActive) && $isActive !== '') {
            return $query->where('is_active', $isActive == '1');
        }
        return $query;
    }
}
