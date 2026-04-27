<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalVisita extends Model
{
    use HasFactory;

    protected $table = 'portal_visitas';

    protected $fillable = [
        'user_id',
        'visitor_key',
        'session_id',
        'ip_address',
        'user_agent',
        'fecha_visita',
    ];

    protected function casts(): array
    {
        return [
            'fecha_visita' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
