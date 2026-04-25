<?php

namespace Database\Seeders;

use App\Models\TipoPropiedad;
use Illuminate\Database\Seeder;

class TipoPropiedadSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tipos = [
            'Terreno',
            'Casa',
            'Departamento',
            'Lote',
            'Local comercial',
            'Chacra',
            'Oficina',
            'Proyecto inmobiliario',
        ];

        foreach ($tipos as $nombre) {
            TipoPropiedad::query()->updateOrCreate(
                ['nombre' => $nombre],
                ['nombre' => $nombre]
            );
        }
    }
}
