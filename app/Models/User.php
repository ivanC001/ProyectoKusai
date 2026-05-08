<?php

namespace App\Models;

use App\Notifications\EmailVerificationCodeNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
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
        'provider',
        'provider_id',
        'avatar',
        'rol',
        'tipo_persona',
        'empresa',
        'nombre_comercial',
        'estado',
        'ultimo_login',
        'solicitudes_vistas_at',
        'email_verification_code_hash',
        'email_verification_code_expires_at',
        'email_verification_code_sent_at',
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
            'solicitudes_vistas_at' => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'email_verification_code_sent_at' => 'datetime',
        ];
    }

    public function issueEmailVerificationCode(int $minutesToExpire = 15): string
    {
        $ttl = max(5, min($minutesToExpire, 60));
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'email_verification_code_hash' => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes($ttl),
            'email_verification_code_sent_at' => now(),
        ])->save();

        return $code;
    }

    public function sendEmailVerificationCode(int $minutesToExpire = 15): void
    {
        $ttl = max(5, min($minutesToExpire, 60));
        $code = $this->issueEmailVerificationCode($ttl);

        $this->notify(new EmailVerificationCodeNotification($code, $ttl));
    }

    public function hasValidEmailVerificationCode(string $code): bool
    {
        $code = trim($code);

        if (
            $code === ''
            || $this->email_verification_code_hash === null
            || $this->email_verification_code_expires_at === null
            || now()->greaterThan($this->email_verification_code_expires_at)
        ) {
            return false;
        }

        return Hash::check($code, $this->email_verification_code_hash);
    }

    public function clearEmailVerificationCode(): void
    {
        $this->forceFill([
            'email_verification_code_hash' => null,
            'email_verification_code_expires_at' => null,
            'email_verification_code_sent_at' => null,
        ])->save();
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

    public function solicitudesContactoEnviadas(): HasMany
    {
        return $this->hasMany(Contacto::class, 'user_id');
    }

    public function comentariosPropiedad(): HasMany
    {
        return $this->hasMany(ComentarioPropiedad::class, 'user_id');
    }

    public function resenasPropiedad(): HasMany
    {
        return $this->hasMany(ResenaPropiedad::class, 'user_id');
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
