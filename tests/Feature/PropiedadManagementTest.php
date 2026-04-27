<?php

namespace Tests\Feature;

use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropiedadManagementTest extends TestCase
{
    use RefreshDatabase;

    private string $csvPath;

    protected function setUp(): void
    {
        parent::setUp();

        $directory = storage_path('framework/testing');
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $this->csvPath = $directory.DIRECTORY_SEPARATOR.'peru-ubigeos-propiedades-test.csv';

        file_put_contents($this->csvPath, implode("\n", [
            '"cod_dep_inei","desc_dep_inei","cod_prov_inei","desc_prov_inei","cod_ubigeo_inei","desc_ubigeo_inei"',
            '"25","UCAYALI","2501","CORONEL PORTILLO","250101","CALLERIA"',
            '"25","UCAYALI","2501","CORONEL PORTILLO","250102","YARINACOCHA"',
            '"17","MADRE DE DIOS","1701","TAMBOPATA","170101","TAMBOPATA"',
        ]));

        config()->set('ubicaciones.peru_ubigeos_csv', $this->csvPath);
    }

    protected function tearDown(): void
    {
        if (is_file($this->csvPath)) {
            @unlink($this->csvPath);
        }

        parent::tearDown();
    }

    public function test_owner_can_open_edit_page_for_property(): void
    {
        [$user, $propiedad] = $this->createPropiedad();

        $response = $this->actingAs($user)->get(route('propiedades.edit', $propiedad));

        $response->assertOk();
        $response->assertSee('Editar publicacion');
        $response->assertSee($propiedad->titulo);
    }

    public function test_owner_can_update_property_data_from_edit_form(): void
    {
        Storage::fake('public');
        [$user, $propiedad] = $this->createPropiedad();

        $tipoNuevo = TipoPropiedad::query()->create(['nombre' => 'Departamento']);
        Storage::disk('public')->put('propiedades/edit-original.jpg', 'original');
        ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/edit-original.jpg',
            'propiedad_id' => $propiedad->id,
        ]);

        $response = $this->actingAs($user)->patch(route('propiedades.update', $propiedad), [
            'titulo' => 'Casa renovada en esquina',
            'descripcion' => 'Casa completamente renovada, lista para mudarse con servicios completos.',
            'precio' => 150000,
            'tipo' => 'venta',
            'estado' => 'reservado',
            'direccion' => 'Av. Peru 456',
            'latitud' => -8.3850000,
            'longitud' => -74.5310000,
            'habitaciones' => 4,
            'banos' => 3,
            'area' => 140,
            'tipo_propiedad_id' => $tipoNuevo->id,
            'departamento' => 'ucayali',
            'provincia' => 'coronel portillo',
            'distrito' => 'yarinacocha',
        ]);

        $response->assertRedirect(route('propiedades.mine'));
        $response->assertSessionHas('success', 'Publicacion actualizada correctamente.');

        $propiedad->refresh();
        $propiedad->load('ubicacion');

        $this->assertSame('Casa renovada en esquina', $propiedad->titulo);
        $this->assertSame('reservado', $propiedad->estado);
        $this->assertSame((string) $tipoNuevo->id, (string) $propiedad->tipo_propiedad_id);
        $this->assertSame('UCAYALI', $propiedad->ubicacion->departamento);
        $this->assertSame('CORONEL PORTILLO', $propiedad->ubicacion->provincia);
        $this->assertSame('YARINACOCHA', $propiedad->ubicacion->distrito);
    }

    public function test_owner_can_see_first_photo_as_portada_through_image_route(): void
    {
        Storage::fake('public');
        [$user, $propiedad] = $this->createPropiedad();

        Storage::disk('public')->put('propiedades/cover-1.jpg', 'first');
        Storage::disk('public')->put('propiedades/cover-2.jpg', 'second');

        $imagen1 = ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/cover-1.jpg',
            'propiedad_id' => $propiedad->id,
        ]);

        ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/cover-2.jpg',
            'propiedad_id' => $propiedad->id,
        ]);

        $propiedad->refresh();
        $portada = $propiedad->portadaImagen()->first();

        $this->assertNotNull($portada);
        $this->assertSame($imagen1->id, $portada->id);

        $response = $this->actingAs($user)->get(route('propiedades.imagen.show', [$propiedad, $portada]));
        $response->assertOk();

        $listResponse = $this->actingAs($user)->get(route('propiedades.mine'));
        $listResponse->assertOk();
        $listResponse->assertSee(route('propiedades.edit', $propiedad));
        $listResponse->assertSee(route('propiedades.imagen.show', [$propiedad, $portada]));
    }

    public function test_owner_can_remove_and_add_photos_from_edit_form(): void
    {
        Storage::fake('public');
        [$user, $propiedad] = $this->createPropiedad();

        Storage::disk('public')->put('propiedades/edit-old-1.jpg', 'old-1');
        Storage::disk('public')->put('propiedades/edit-old-2.jpg', 'old-2');

        $imagen1 = ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/edit-old-1.jpg',
            'propiedad_id' => $propiedad->id,
        ]);
        ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/edit-old-2.jpg',
            'propiedad_id' => $propiedad->id,
        ]);

        $response = $this->actingAs($user)->patch(route('propiedades.update', $propiedad), [
            'titulo' => $propiedad->titulo,
            'descripcion' => $propiedad->descripcion,
            'precio' => $propiedad->precio,
            'tipo' => $propiedad->tipo,
            'estado' => $propiedad->estado,
            'direccion' => $propiedad->direccion,
            'latitud' => $propiedad->latitud,
            'longitud' => $propiedad->longitud,
            'habitaciones' => $propiedad->habitaciones,
            'banos' => $propiedad->banos,
            'area' => $propiedad->area,
            'tipo_propiedad_id' => $propiedad->tipo_propiedad_id,
            'departamento' => 'UCAYALI',
            'provincia' => 'CORONEL PORTILLO',
            'distrito' => 'CALLERIA',
            'remover_imagenes' => [$imagen1->id],
            'nuevas_fotos' => [
                UploadedFile::fake()->image('new-1.jpg', 600, 400),
                UploadedFile::fake()->image('new-2.jpg', 600, 400),
            ],
        ]);

        $response->assertRedirect(route('propiedades.mine'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('imagenes_propiedad', ['id' => $imagen1->id]);

        $propiedad->refresh();
        $this->assertSame(3, $propiedad->imagenes()->count());
    }

    public function test_owner_cannot_remove_all_photos_from_edit_form(): void
    {
        Storage::fake('public');
        [$user, $propiedad] = $this->createPropiedad();

        Storage::disk('public')->put('propiedades/edit-last.jpg', 'last');
        $imagen = ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/edit-last.jpg',
            'propiedad_id' => $propiedad->id,
        ]);

        $response = $this->actingAs($user)->from(route('propiedades.edit', $propiedad))
            ->patch(route('propiedades.update', $propiedad), [
                'titulo' => $propiedad->titulo,
                'descripcion' => $propiedad->descripcion,
                'precio' => $propiedad->precio,
                'tipo' => $propiedad->tipo,
                'estado' => $propiedad->estado,
                'direccion' => $propiedad->direccion,
                'latitud' => $propiedad->latitud,
                'longitud' => $propiedad->longitud,
                'habitaciones' => $propiedad->habitaciones,
                'banos' => $propiedad->banos,
                'area' => $propiedad->area,
                'tipo_propiedad_id' => $propiedad->tipo_propiedad_id,
                'departamento' => 'UCAYALI',
                'provincia' => 'CORONEL PORTILLO',
                'distrito' => 'CALLERIA',
                'remover_imagenes' => [$imagen->id],
            ]);

        $response->assertRedirect(route('propiedades.edit', $propiedad));
        $response->assertSessionHasErrors('nuevas_fotos');
        $this->assertDatabaseHas('imagenes_propiedad', ['id' => $imagen->id]);
    }

    /**
     * @return array{0: User, 1: Propiedad}
     */
    private function createPropiedad(): array
    {
        $user = User::factory()->create();
        $tipo = TipoPropiedad::query()->create(['nombre' => 'Casa']);
        $ubicacion = Ubicacion::query()->create([
            'departamento' => 'UCAYALI',
            'provincia' => 'CORONEL PORTILLO',
            'distrito' => 'CALLERIA',
        ]);

        $propiedad = Propiedad::query()->create([
            'titulo' => 'Casa zona centrica',
            'descripcion' => 'Casa amplia y bien ubicada con todos los servicios basicos cerca.',
            'precio' => 12500,
            'tipo' => 'venta',
            'estado' => 'disponible',
            'direccion' => 'Av. Principal 123',
            'latitud' => -8.38,
            'longitud' => -74.54,
            'habitaciones' => 3,
            'banos' => 2,
            'area' => 120,
            'user_id' => $user->id,
            'tipo_propiedad_id' => $tipo->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        return [$user, $propiedad];
    }
}
