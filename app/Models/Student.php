<?php

namespace App\Models;

use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes, Reportable;

    protected $table = 'students';

    protected $fillable = [
        'person_id',
        'registration',
        'entry_date',
        'is_active',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_active' => 'boolean',
    ];

    public static function getReportLabel(): string
    {
        return 'Alunos';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'person.name',
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
            'person.name' => 'Nome do Aluno',
            'registration' => 'Matrícula',
            'is_active' => 'Ativo',
            'entry_date' => 'Data de Ingresso',
            'person.email' => 'E-mail',
            'person.document' => 'CPF/Documento',
            'created_at' => 'Cadastrado em',
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function scopeName(Builder $query, ?string $term): Builder
    {
        return $term ? $query->whereHas('person', fn($q) => $q->where('name', 'like', "%{$term}%")) : $query;
    }

    public function scopeRegistration(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('registration', 'like', "%{$term}%") : $query;
    }

    public function scopeActive(Builder $query, $isActive): Builder
    {
        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', $isActive == '1');
        }
        return $query;
    }

    public function scopeEmail(Builder $query, ?string $term): Builder
    {
        return $term
            ? $query->whereHas('person', function ($q) use ($term) {
                $q->where('email', 'like', "%{$term}%");
            })
            : $query;
    }
}
