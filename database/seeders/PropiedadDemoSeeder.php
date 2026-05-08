<?php

namespace Database\Seeders;

use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropiedadDemoSeeder extends Seeder
{
    /**
     * Seed demo properties: 2 publicaciones por cada tipo de propiedad.
     */
    public function run(): void
    {
        $owners = [
            User::query()->updateOrCreate(
                ['email' => 'usuario1@gmail.com'],
                [
                    'name' => 'Carlos',
                    'apellidos' => 'Rojas',
                    'password' => Hash::make('123123123'),
                    'rol' => 'cliente',
                    'tipo_persona' => 'natural',
                    'estado' => 'activo',
                    'telefono' => '900111222',
                    'whatsapp' => '900111222',
                ]
            ),
            User::query()->updateOrCreate(
                ['email' => 'usuario2@gmail.com'],
                [
                    'name' => 'Lucia',
                    'apellidos' => 'Torres',
                    'password' => Hash::make('123123123'),
                    'rol' => 'cliente',
                    'tipo_persona' => 'natural',
                    'estado' => 'activo',
                    'telefono' => '900333444',
                    'whatsapp' => '900333444',
                ]
            ),
        ];

        $ubicacionesBase = [
            ['departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Miraflores', 'lat' => -12.1219668, 'lng' => -77.0296838],
            ['departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'La Victoria', 'lat' => -12.0703986, 'lng' => -77.0169533],
            ['departamento' => 'Ucayali', 'provincia' => 'Coronel Portillo', 'distrito' => 'Calleria', 'lat' => -8.3791476, 'lng' => -74.5538757],
            ['departamento' => 'Ucayali', 'provincia' => 'Coronel Portillo', 'distrito' => 'Yarinacocha', 'lat' => -8.3546748, 'lng' => -74.5743209],
            ['departamento' => 'Loreto', 'provincia' => 'Maynas', 'distrito' => 'Iquitos', 'lat' => -3.7436735, 'lng' => -73.2516326],
            ['departamento' => 'San Martin', 'provincia' => 'San Martin', 'distrito' => 'Tarapoto', 'lat' => -6.4824695, 'lng' => -76.3659674],
            ['departamento' => 'Junin', 'provincia' => 'Huancayo', 'distrito' => 'Huancayo', 'lat' => -12.0651322, 'lng' => -75.2048608],
            ['departamento' => 'Lambayeque', 'provincia' => 'Chiclayo', 'distrito' => 'Chiclayo', 'lat' => -6.7700749, 'lng' => -79.8366455],
        ];

        $tipos = TipoPropiedad::query()->orderBy('id')->get();
        if ($tipos->isEmpty()) {
            return;
        }

        $ubicacionCursor = 0;

        foreach ($tipos as $tipoPropiedad) {
            $esProyecto = Str::contains(Str::lower($tipoPropiedad->nombre), 'proyecto');

            for ($i = 1; $i <= 2; $i++) {
                $owner = $owners[($i - 1) % count($owners)];
                $base = $ubicacionesBase[$ubicacionCursor % count($ubicacionesBase)];
                $ubicacionCursor++;

                $ubicacion = Ubicacion::query()->firstOrCreate([
                    'departamento' => $base['departamento'],
                    'provincia' => $base['provincia'],
                    'distrito' => $base['distrito'],
                ]);

                $operacion = $esProyecto ? 'venta' : ($i === 1 ? 'venta' : 'alquiler');
                $tituloTipo = $esProyecto ? 'Proyecto inmobiliario' : $tipoPropiedad->nombre;
                $titulo = $tituloTipo.' demo '.$i.' - '.$base['distrito'];

                $precio = 80000 + (($tipoPropiedad->id * 2 + $i) * 7500);

                $propiedad = Propiedad::query()->updateOrCreate(
                    [
                        'titulo' => $titulo,
                        'user_id' => $owner->id,
                    ],
                    [
                        'descripcion' => $this->descripcionDemo($tipoPropiedad->nombre, $base['distrito'], $operacion, $esProyecto),
                        'precio' => $precio,
                        'precio_usd' => round($precio / 3.75, 2),
                        'tipo' => $operacion,
                        'estado' => 'disponible',
                        'direccion' => 'Av. Demo '.$i.' Nro. '.(100 + $tipoPropiedad->id).', '.$base['distrito'],
                        'referencia' => 'A 2 cuadras de la plaza principal de '.$base['distrito'],
                        'latitud' => $base['lat'],
                        'longitud' => $base['lng'],
                        'habitaciones' => $esProyecto ? 0 : (1 + $i),
                        'banos' => $esProyecto ? 0 : $i,
                        'area' => $esProyecto ? (450 + ($i * 30)) : (85 + ($i * 15)),
                        'tipo_propiedad_id' => $tipoPropiedad->id,
                        'ubicacion_id' => $ubicacion->id,
                    ]
                );

                $this->seedPlaceholderImages($propiedad, $tipoPropiedad->nombre, $i);
            }
        }
    }

    private function descripcionDemo(string $tipoNombre, string $distrito, string $operacion, bool $esProyecto): string
    {
        if ($esProyecto) {
            return 'Proyecto inmobiliario en '.$distrito.'. Incluye etapas de entrega, metrajes variados y precios desde. Publicacion demo para pruebas del portal.';
        }

        return $tipoNombre.' en '.$distrito.' para '.$operacion.'. Cuenta con excelente ubicacion y descripcion de prueba para validar listados, filtros y detalle.';
    }

    private function seedPlaceholderImages(Propiedad $propiedad, string $tipoNombre, int $indicePublicacion): void
    {
        for ($j = 1; $j <= 2; $j++) {
            $slugTipo = Str::slug($tipoNombre);
            $ruta = 'seeders/propiedades/'.$slugTipo.'-'.$propiedad->id.'-'.$indicePublicacion.'-'.$j.'.svg';

            if (! Storage::disk('public')->exists($ruta)) {
                $svg = $this->buildPlaceholderSvg($tipoNombre, $j);
                Storage::disk('public')->put($ruta, $svg);
            }

            ImagenPropiedad::query()->updateOrCreate(
                [
                    'propiedad_id' => $propiedad->id,
                    'ruta_imagen' => $ruta,
                ],
                [
                    'ruta_imagen' => $ruta,
                ]
            );
        }
    }

    private function buildPlaceholderSvg(string $tipoNombre, int $fotoNumero): string
    {
        $label = htmlspecialchars(Str::upper($tipoNombre), ENT_QUOTES, 'UTF-8');
        $foto = 'FOTO '.$fotoNumero;

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1280" height="720" viewBox="0 0 1280 720">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#0f4d35"/>
      <stop offset="100%" stop-color="#1b6c49"/>
    </linearGradient>
  </defs>
  <rect width="1280" height="720" fill="url(#bg)"/>
  <text x="60" y="130" fill="#d8f0e4" font-family="Arial, sans-serif" font-size="42" font-weight="700">KUSAY.PE</text>
  <text x="60" y="360" fill="#ffffff" font-family="Arial, sans-serif" font-size="64" font-weight="800">{$label}</text>
  <text x="60" y="430" fill="#d8f0e4" font-family="Arial, sans-serif" font-size="34" font-weight="600">{$foto}</text>
</svg>
SVG;
    }
}

