<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResenaPropiedad extends Model
{
    use HasFactory;

    protected $table = 'resenas_propiedad';

    protected $fillable = [
        'propiedad_id',
        'user_id',
        'puntaje',
        'comentario',
    ];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
