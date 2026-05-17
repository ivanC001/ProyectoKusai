<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificacionPropiedad extends Model
{
    use HasFactory;

    protected $table = 'verificaciones_propiedad';

    protected $fillable = [
        'propiedad_id',
        'documentos_revisados',
        'visita_confirmada',
        'vendedor_identificado',
        'verificado_por',
        'fecha_verificacion',
    ];

    protected function casts(): array
    {
        return [
            'documentos_revisados' => 'boolean',
            'visita_confirmada' => 'boolean',
            'vendedor_identificado' => 'boolean',
            'fecha_verificacion' => 'datetime',
        ];
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    public function estaCompleta(): bool
    {
        return $this->documentos_revisados
            && $this->visita_confirmada
            && $this->vendedor_identificado;
    }
}

