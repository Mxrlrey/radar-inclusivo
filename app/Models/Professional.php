<?php

namespace App\Models;

use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professional extends Model
{
    use HasFactory, SoftDeletes, Reportable;

    /** Estrutura base da model. */
    protected $table = 'professionals';

    protected $fillable = [
        'person_id',
        'position_id',
        'registration',
        'entry_date',
        'is_active',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_active' => 'boolean',
    ];

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Profissionais';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'person.name',
            'position.name',
            'registration',
            'is_active',
            'entry_date',
            'person.email',
            'person.document',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id' => 'ID',
            'person.name' => 'Nome do Profissional',
            'position.name' => 'Cargo',
            'registration' => 'Matrícula',
            'is_active' => 'Ativo',
            'entry_date' => 'Data de Ingresso',
            'person.email' => 'E-mail',
            'person.document' => 'CPF',
            'created_at' => 'Data de Cadastro',
        ];
    }

    /** Relacionamentos. */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /** Scopes e filtros. */
    public function scopeName(Builder $query, ?string $term): Builder
    {
        return $term ? $query->whereHas('person', fn($q) => $q->where('name', 'like', "%{$term}%")) : $query;
    }

    public function scopeEmail(Builder $query, ?string $term): Builder
    {
        return $term ? $query->whereHas('person', fn($q) => $q->where('email', 'like', "%{$term}%")) : $query;
    }

    public function scopePosition(Builder $query, $positionId): Builder
    {
        if (!is_null($positionId) && $positionId !== '') {
            $query->where('position_id', $positionId);
        }
        return $query;
    }

    public function scopeActive(Builder $query, $isActive): Builder
    {
        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', $isActive == '1');
        }
        return $query;
    }
}
