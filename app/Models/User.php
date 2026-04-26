<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** Estrutura base da model. */
    protected $fillable = [
        'name',
        'email',
        'password',
        'professional_id',
        'is_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** Relacionamentos. */
    public function professional()
    {
        return $this->belongsTo(Professional::class)->withTrashed();
    }

    /** Atributos derivados e regras auxiliares. */
    public function getNameAttribute()
    {
        return $this->professional?->person?->name
            ?? $this->attributes['name'];
    }

    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }

    public function getPhotoUrlAttribute()
    {
        $photoUrl = $this->professional?->person?->photo_url;

        return $photoUrl ?? asset('images/default-user.jpg');
    }

    public function hasPermission(string $permissionSlug): bool
    {

        if ($this->is_admin) return true;

        $hasProfessionalPermission = $this->professional
            ?->position
            ?->permissions
            ->contains('slug', $permissionSlug) ?? false;

        if ($hasProfessionalPermission) return true;

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isImpersonating()
    {
        return session()->has('impersonator_id');
    }

    /** Configuração de casts. */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
