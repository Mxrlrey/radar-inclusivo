<?php

namespace App\Models;

use App\Models\Traits\Reportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Waitlist extends Model
{
    use HasFactory, Reportable;

    /** Estrutura base da model. */
    protected $fillable = [
        'waitlistable_id',
        'waitlistable_type',
        'student_id',
        'professional_id',
        'user_id',
        'requested_at',
        'status',
        'observation',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /** Configuração do builder de relatórios. */
    public static function getReportLabel(): string
    {
        return 'Lista de Espera';
    }

    public static function getReportColumns(): array
    {
        return [
            'id',
            'status',
            'observation',
            'requested_at',
            'created_at',
        ];
    }

    public static function getReportColumnLabels(): array
    {
        return [
            'id'           => 'ID',
            'status'       => 'Status',
            'observation'  => 'Observação',
            'requested_at' => 'Data da Solicitação',
            'created_at'   => 'Data de Cadastro',
        ];
    }

    /** Contrato de relações especiais para relatórios. */
    public static function getReportRelations(): array
    {
        return [
            'assistiveTechnology' => [
                'relation' => 'waitlistable',
                'type_column' => 'waitlistable_type',
                'target' => AssistiveTechnology::class,
            ],
            'accessibleEducationalMaterial' => [
                'relation' => 'waitlistable',
                'type_column' => 'waitlistable_type',
                'target' => AccessibleEducationalMaterial::class,
            ],
        ];
    }

    /** Relacionamentos. */
    public function waitlistable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assistiveTechnology(): BelongsTo
    {
        return $this->belongsTo(AssistiveTechnology::class, 'waitlistable_id');
    }

    public function accessibleEducationalMaterial(): BelongsTo
    {
        return $this->belongsTo(AccessibleEducationalMaterial::class, 'waitlistable_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Scopes e filtros. */
    public function scopeItem($query, $name = null)
    {
        if (!$name) return $query;

        $name = strtolower($name);

        return $query->whereHasMorph(
            'waitlistable',
            [AssistiveTechnology::class, AccessibleEducationalMaterial::class],
            function ($q) use ($name) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$name}%"]);
            }
        );
    }

    public function scopeStudent($query, $name = null)
    {
        if (!$name) return $query;

        return $query->whereHas('student.person', function ($q) use ($name) {
            $q->where('name', 'like', "%$name%");
        });
    }

    public function scopeProfessional($query, $name = null)
    {
        if (!$name) return $query;

        return $query->whereHas('professional.person', function ($q) use ($name) {
            $q->where('name', 'like', "%$name%");
        });
    }
}
