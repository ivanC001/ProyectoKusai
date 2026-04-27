<?php

namespace Tests\Feature;

use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_with_dynamic_property_cards(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Portal inmobiliario');
        $response->assertSee($propiedadVisible->titulo);
    }

    public function test_home_registers_unique_portal_visit_per_session(): void
    {
        $this->seedPropiedades();

        $firstResponse = $this->get(route('home'));
        $firstResponse->assertOk();
        $firstResponse->assertSee('Usuarios que visitaron el portal');
        $this->assertDatabaseCount('portal_visitas', 1);

        $secondResponse = $this->get(route('home'));
        $secondResponse->assertOk();
        $this->assertDatabaseCount('portal_visitas', 1);
    }

    public function test_home_filters_by_operation_and_type(): void
    {
        [$ventaCasa, $alquilerDepartamento] = $this->seedPropiedades();

        $response = $this->get(route('home', [
            'operacion' => 'alquiler',
            'tipo_propiedad_id' => $alquilerDepartamento->tipo_propiedad_id,
        ]));

        $response->assertOk();
        $response->assertSee($alquilerDepartamento->titulo);
        $response->assertDontSee($ventaCasa->titulo);
    }

    public function test_property_detail_is_public_only_for_available_properties(): void
    {
        [$propiedadVisible, , $propiedadNoDisponible] = $this->seedPropiedades();

        $visibleResponse = $this->get(route('portal.propiedades.show', $propiedadVisible));
        $visibleResponse->assertOk();
        $visibleResponse->assertSee($propiedadVisible->titulo);

        $hiddenResponse = $this->get(route('portal.propiedades.show', $propiedadNoDisponible));
        $hiddenResponse->assertNotFound();
    }

    public function test_public_image_route_returns_property_image(): void
    {
        Storage::fake('public');
        [$propiedadVisible] = $this->seedPropiedades();

        Storage::disk('public')->put('propiedades/portal-cover.jpg', 'cover-image');

        $imagen = ImagenPropiedad::query()->create([
            'ruta_imagen' => 'propiedades/portal-cover.jpg',
            'propiedad_id' => $propiedadVisible->id,
        ]);

        $response = $this->get(route('portal.propiedades.imagen', [$propiedadVisible, $imagen]));

        $response->assertOk();
    }

    public function test_property_click_endpoint_registers_click(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();

        $response = $this->postJson(route('portal.propiedades.click', $propiedadVisible));

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
        ]);
        $this->assertDatabaseHas('visitas', [
            'propiedad_id' => $propiedadVisible->id,
        ]);
    }

    public function test_authenticated_user_can_toggle_property_favorite(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();
        $user = User::factory()->create();
        $this->actingAs($user);

        $agregarResponse = $this->postJson(route('portal.propiedades.favoritos.toggle', $propiedadVisible));
        $agregarResponse->assertOk();
        $agregarResponse->assertJson([
            'ok' => true,
            'favorita' => true,
        ]);
        $this->assertDatabaseHas('favoritos', [
            'user_id' => $user->id,
            'propiedad_id' => $propiedadVisible->id,
        ]);

        $quitarResponse = $this->postJson(route('portal.propiedades.favoritos.toggle', $propiedadVisible));
        $quitarResponse->assertOk();
        $quitarResponse->assertJson([
            'ok' => true,
            'favorita' => false,
        ]);
        $this->assertDatabaseMissing('favoritos', [
            'user_id' => $user->id,
            'propiedad_id' => $propiedadVisible->id,
        ]);
    }

    public function test_guest_cannot_toggle_property_favorite(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();

        $response = $this->postJson(route('portal.propiedades.favoritos.toggle', $propiedadVisible));

        $response->assertUnauthorized();
        $this->assertDatabaseCount('favoritos', 0);
    }

    /**
     * @return array{0: Propiedad, 1: Propiedad, 2: Propiedad}
     */
    private function seedPropiedades(): array
    {
        $user = User::factory()->create();
        $tipoCasa = TipoPropiedad::query()->create(['nombre' => 'Casa']);
        $tipoDepartamento = TipoPropiedad::query()->create(['nombre' => 'Departamento']);
        $ubicacion = Ubicacion::query()->create([
            'departamento' => 'UCAYALI',
            'provincia' => 'CORONEL PORTILLO',
            'distrito' => 'CALLERIA',
        ]);

        $ventaCasa = Propiedad::query()->create([
            'titulo' => 'Casa familiar en esquina',
            'descripcion' => 'Casa amplia para familia grande en zona centrica.',
            'precio' => 140000,
            'tipo' => 'venta',
            'estado' => 'disponible',
            'direccion' => 'Jr. Central 101',
            'habitaciones' => 4,
            'banos' => 2,
            'area' => 160,
            'user_id' => $user->id,
            'tipo_propiedad_id' => $tipoCasa->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        $alquilerDepartamento = Propiedad::query()->create([
            'titulo' => 'Departamento con balcon',
            'descripcion' => 'Departamento moderno con balcon y buena iluminacion natural.',
            'precio' => 2200,
            'tipo' => 'alquiler',
            'estado' => 'disponible',
            'direccion' => 'Av. Los Pinos 445',
            'habitaciones' => 2,
            'banos' => 2,
            'area' => 82,
            'user_id' => $user->id,
            'tipo_propiedad_id' => $tipoDepartamento->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        $noDisponible = Propiedad::query()->create([
            'titulo' => 'Casa vendida',
            'descripcion' => 'Propiedad vendida que no debe salir en el portal.',
            'precio' => 100000,
            'tipo' => 'venta',
            'estado' => 'vendido',
            'direccion' => 'Psj. Antiguo 33',
            'habitaciones' => 3,
            'banos' => 2,
            'area' => 120,
            'user_id' => $user->id,
            'tipo_propiedad_id' => $tipoCasa->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        return [$ventaCasa, $alquilerDepartamento, $noDisponible];
    }
}
