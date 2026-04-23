<?php

namespace App\Models;

use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory, SoftDeletes, Reportable;

    /** Estrutura base da model. */
    protected $table = 'institutions';

    protected $fillable = [
        'name',
        'short_name',
        'city',
        'state',
        'district',
        'address',
        'latitude',
        'longitude',
        'default_zoom',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'default_zoom' => 'integer',
        'is_active' => 'boolean',
    ];

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Instituições';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'name',
            'short_name',
            'city',
            'state',
            'district',
            'address',
            'is_active',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id'         => 'ID',
            'name'       => 'Nome',
            'short_name' => 'Nome Abreviado',
            'city'       => 'Cidade',
            'state'      => 'Estado',
            'district'   => 'Bairro',
            'address'    => 'Endereço',
            'is_active'  => 'Ativo',
            'created_at' => 'Data de Cadastro',
        ];
    }

    /** Relacionamentos. */
    public function latestInspection(): MorphOne
    {
        return $this->morphOne(Inspection::class, 'inspectable')
            ->latestOfMany('inspection_date');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function barriers(): HasMany
    {
        return $this->hasMany(Barrier::class);
    }

    /** Scopes e filtros. */
    public function scopeFilterName($query, ?string $name)
    {
        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }
    }

    public function scopeFilterStatus($query, $status)
    {
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }
    }

    public function scopeFilterLocation($query, ?string $location)
    {
        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                    ->orWhere('state', 'like', "%{$location}%");
            });
        }
    }
}
