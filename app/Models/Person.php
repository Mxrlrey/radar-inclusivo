<?php

namespace App\Models;

use App\Enums\Gender;
use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes, Reportable;

    /** Estrutura base da model. */
    protected $table = 'people';

    protected $fillable = [
        'name',
        'document',
        'birth_date',
        'gender',
        'email',
        'phone',
        'address',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'gender'     => Gender::class,
    ];

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Pessoas';
    }

    public static function getReportColumns(): array
    {
        return ['id', 'name', 'document', 'email', 'phone', 'birth_date', 'gender', 'created_at'];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nome Completo',
            'document' => 'CPF',
            'email' => 'E-mail',
            'phone' => 'Telefone',
            'birth_date' => 'Data de Nascimento',
            'gender' => 'Gênero',
            'created_at' => 'Data de Cadastro',
        ];
    }

    /** Atributos derivados e regras auxiliares. */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender->label();
    }

    /** Relacionamentos. */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function professional(): HasOne
    {
        return $this->hasOne(Professional::class);
    }

    /** Scopes e filtros. */
    public function scopeName(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('name', 'like', "%{$term}%") : $query;
    }

    public function scopeDocument(Builder $query, ?string $term): Builder
    {
        $term = preg_replace('/[^0-9]/', '', $term);
        return $term ? $query->where('document', 'like', "%{$term}%") : $query;
    }

    public function scopeEmail(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('email', 'like', "%{$term}%") : $query;
    }

    public function getDocumentFormattedAttribute(): string
    {
        $doc = preg_replace('/\D/', '', $this->document);

        if (strlen($doc) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $doc);
        }

        return $this->document;
    }
}
