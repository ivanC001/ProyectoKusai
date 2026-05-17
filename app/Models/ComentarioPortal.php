<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioPortal extends Model
{
    use HasFactory;

    protected $table = 'comentarios_portal';

    protected $fillable = [
        'user_id',
        'puntaje',
        'comentario',
        'sugerencia',
        'visible',
    ];

    protected function casts(): array
    {
        return [
            'puntaje' => 'integer',
            'visible' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
