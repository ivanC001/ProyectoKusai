<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPropiedad extends Model
{
    use HasFactory;

    protected $table = 'tipos_propiedad';

    protected $fillable = [
        'nombre',
    ];

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class, 'tipo_propiedad_id');
    }
}
