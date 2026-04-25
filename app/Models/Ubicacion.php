<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'departamento',
        'provincia',
        'distrito',
    ];

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class, 'ubicacion_id');
    }
}
