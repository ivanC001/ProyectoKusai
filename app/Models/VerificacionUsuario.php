<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificacionUsuario extends Model
{
    use HasFactory;

    protected $table = 'verificaciones_usuario';

    protected $fillable = [
        'user_id',
        'dni',
        'dni_frontal_path',
        'dni_reverso_path',
        'dni_legible',
        'datos_coinciden',
        'contacto_validado',
        'estado',
        'observaciones',
        'verificado_por',
        'fecha_verificacion',
    ];

    protected function casts(): array
    {
        return [
            'dni_legible' => 'boolean',
            'datos_coinciden' => 'boolean',
            'contacto_validado' => 'boolean',
            'fecha_verificacion' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    public function estaCompleta(): bool
    {
        return $this->dni_legible
            && $this->datos_coinciden
            && $this->contacto_validado;
    }

    public function estaAprobada(): bool
    {
        return $this->estado === 'aprobado' && $this->estaCompleta();
    }
}

