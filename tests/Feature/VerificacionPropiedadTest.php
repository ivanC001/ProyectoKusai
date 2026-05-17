<?php

namespace Tests\Feature;

use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\User;
use App\Models\VerificacionPropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificacionPropiedadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_property_verification(): void
    {
        $admin = User::factory()->create([
            'rol' => 'admin',
            'estado' => 'activo',
        ]);
        $propiedad = $this->crearPropiedad();

        $response = $this->actingAs($admin)->patch(
            route('admin.verificaciones-propiedad.update', $propiedad),
            [
                'documentos_revisados' => '1',
                'visita_confirmada' => '1',
                'vendedor_identificado' => '1',
            ]
        );

        $response->assertRedirect(route('admin.verificaciones-propiedad.edit', $propiedad));

        $this->assertDatabaseHas('verificaciones_propiedad', [
            'propiedad_id' => $propiedad->id,
            'documentos_revisados' => 1,
            'visita_confirmada' => 1,
            'vendedor_identificado' => 1,
            'verificado_por' => $admin->id,
        ]);
    }

    public function test_non_admin_user_cannot_update_property_verification(): void
    {
        $usuario = User::factory()->create([
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);
        $propiedad = $this->crearPropiedad();

        $response = $this->actingAs($usuario)->patch(
            route('admin.verificaciones-propiedad.update', $propiedad),
            [
                'documentos_revisados' => '1',
                'visita_confirmada' => '1',
                'vendedor_identificado' => '1',
            ]
        );

        $response->assertForbidden();
        $this->assertDatabaseCount('verificaciones_propiedad', 0);
    }

    public function test_portal_show_displays_verified_badge_when_verification_is_complete(): void
    {
        $propiedad = $this->crearPropiedad();

        VerificacionPropiedad::query()->create([
            'propiedad_id' => $propiedad->id,
            'documentos_revisados' => true,
            'visita_confirmada' => true,
            'vendedor_identificado' => true,
        ]);

        $response = $this->get(route('portal.propiedades.show', $propiedad));

        $response->assertOk();
        $response->assertSee('✅ Verificado por Kusay', false);
    }

    private function crearPropiedad(): Propiedad
    {
        $usuario = User::factory()->create();
        $tipo = TipoPropiedad::query()->create(['nombre' => 'Casa']);
        $ubicacion = Ubicacion::query()->create([
            'departamento' => 'UCAYALI',
            'provincia' => 'CORONEL PORTILLO',
            'distrito' => 'CALLERIA',
        ]);

        return Propiedad::query()->create([
            'titulo' => 'Casa de prueba',
            'descripcion' => 'Descripcion de prueba para verificacion.',
            'precio' => 150000,
            'tipo' => 'venta',
            'estado' => 'disponible',
            'direccion' => 'Jr. Prueba 123',
            'habitaciones' => 3,
            'banos' => 2,
            'area' => 140,
            'user_id' => $usuario->id,
            'tipo_propiedad_id' => $tipo->id,
            'ubicacion_id' => $ubicacion->id,
        ]);
    }
}

