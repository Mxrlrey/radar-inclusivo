<?php

namespace App\Models;

use App\Enums\Gender;
use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes, Reportable;

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

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-user.jpg');
    }

    /**
     * Retorna o label amigável do Gênero
     */
    public function getGenderLabelAttribute(): string
    {
        // Como o campo está no $casts, $this->gender já é uma instância do Enum
        return $this->gender->label();
    }

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
}
