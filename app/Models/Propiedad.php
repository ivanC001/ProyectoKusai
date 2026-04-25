<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propiedad extends Model
{
    use HasFactory;

    protected $table = 'propiedades';

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'tipo',
        'estado',
        'direccion',
        'latitud',
        'longitud',
        'habitaciones',
        'banos',
        'area',
        'user_id',
        'tipo_propiedad_id',
        'ubicacion_id',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
            'area' => 'decimal:2',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tipoPropiedad(): BelongsTo
    {
        return $this->belongsTo(TipoPropiedad::class, 'tipo_propiedad_id');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenPropiedad::class, 'propiedad_id');
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class, 'propiedad_id');
    }

    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class, 'propiedad_id');
    }

    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class, 'propiedad_id');
    }

    public function usuariosFavoritos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos', 'propiedad_id', 'user_id')->withTimestamps();
    }
}
