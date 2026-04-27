<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'password',
        'dni',
        'ruc',
        'telefono',
        'whatsapp',
        'direccion',
        'foto_perfil',
        'descripcion',
        'rol',
        'tipo_persona',
        'empresa',
        'nombre_comercial',
        'estado',
        'ultimo_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ultimo_login' => 'datetime',
        ];
    }

    public function esEmpresa(): bool
    {
        return $this->tipo_persona === 'empresa';
    }

    public function esCliente(): bool
    {
        return $this->rol === 'cliente';
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esPersonaNatural(): bool
    {
        return $this->tipo_persona === 'natural';
    }

    public function tieneFotoPerfil(): bool
    {
        return ! empty($this->foto_perfil) && Storage::disk('public')->exists($this->foto_perfil);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class, 'user_id');
    }

    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class, 'user_id');
    }

    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class, 'user_id');
    }

    public function visitasPortal(): HasMany
    {
        return $this->hasMany(PortalVisita::class, 'user_id');
    }

    public function propiedadesFavoritas(): BelongsToMany
    {
        return $this->belongsToMany(Propiedad::class, 'favoritos', 'user_id', 'propiedad_id')->withTimestamps();
    }
}
