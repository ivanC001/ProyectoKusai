<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioPropiedad extends Model
{
    use HasFactory;

    protected $table = 'comentarios_propiedad';

    protected $fillable = [
        'propiedad_id',
        'user_id',
        'puntaje',
        'mensaje',
    ];

    protected function casts(): array
    {
        return [
            'puntaje' => 'integer',
        ];
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
