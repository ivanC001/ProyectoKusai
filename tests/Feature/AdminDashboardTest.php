<?php

namespace Tests\Feature;

use App\Models\Favorito;
use App\Models\ComentarioPortal;
use App\Models\PortalVisita;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\User;
use App\Models\Visita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_dashboard_with_metrics_and_publications(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $cliente = User::factory()->create(['rol' => 'cliente']);
        $agente = User::factory()->create(['rol' => 'agente']);

        $tipo = TipoPropiedad::query()->create(['nombre' => 'Terreno']);
        $ubicacion = Ubicacion::query()->create([
            'departamento' => 'UCAYALI',
            'provincia' => 'CORONEL PORTILLO',
            'distrito' => 'CALLERIA',
        ]);

        $propiedad = Propiedad::query()->create([
            'titulo' => 'Terreno campestre',
            'descripcion' => 'Terreno amplio para proyecto inmobiliario.',
            'precio' => 250000,
            'tipo' => 'venta',
            'estado' => 'disponible',
            'direccion' => 'Jr. Principal 123',
            'user_id' => $cliente->id,
            'tipo_propiedad_id' => $tipo->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        Visita::query()->create([
            'propiedad_id' => $propiedad->id,
            'user_id' => $agente->id,
            'fecha_visita' => now(),
        ]);
        Visita::query()->create([
            'propiedad_id' => $propiedad->id,
            'user_id' => null,
            'fecha_visita' => now(),
        ]);

        Favorito::query()->create([
            'user_id' => $agente->id,
            'propiedad_id' => $propiedad->id,
        ]);

        PortalVisita::query()->create([
            'user_id' => $cliente->id,
            'visitor_key' => 'user:'.$cliente->id,
            'session_id' => 'session-1',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'testing',
            'fecha_visita' => now(),
        ]);
        PortalVisita::query()->create([
            'user_id' => null,
            'visitor_key' => 'session:abc',
            'session_id' => 'session-2',
            'ip_address' => '127.0.0.2',
            'user_agent' => 'testing',
            'fecha_visita' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Usuarios Totales');
        $response->assertSee('Visitas Al Portal');
        $response->assertSee('Publicaciones con mayores clics');
        $response->assertSee('Rendimiento por publicacion');
        $response->assertSee('Terreno campestre');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_unverified_admin_is_redirected_to_verification_notice(): void
    {
        $admin = User::factory()->unverified()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_admin_can_update_tipo_propiedad(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $tipo = TipoPropiedad::query()->create(['nombre' => 'Terreno']);

        $response = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.tipos.update', $tipo), [
            'nombre' => 'Terreno urbano',
        ]);

        $response->assertRedirect(route('admin.PanelAdministrativo'));
        $this->assertDatabaseHas('tipos_propiedad', [
            'id' => $tipo->id,
            'nombre' => 'Terreno urbano',
        ]);
    }

    public function test_non_admin_cannot_update_tipo_propiedad(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);
        $tipo = TipoPropiedad::query()->create(['nombre' => 'Terreno']);

        $response = $this->actingAs($cliente)->patch(route('admin.PanelAdministrativo.tipos.update', $tipo), [
            'nombre' => 'Terreno urbano',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('tipos_propiedad', [
            'id' => $tipo->id,
            'nombre' => 'Terreno',
        ]);
    }

    public function test_admin_can_block_and_unblock_user(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $cliente = User::factory()->create(['rol' => 'cliente', 'estado' => 'activo']);

        $bloquearResponse = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.usuarios.estado.update', $cliente), [
            'estado' => 'inactivo',
        ]);

        $bloquearResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $cliente->id,
            'estado' => 'inactivo',
        ]);

        $activarResponse = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.usuarios.estado.update', $cliente), [
            'estado' => 'activo',
        ]);

        $activarResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $cliente->id,
            'estado' => 'activo',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($admin)->delete(route('admin.PanelAdministrativo.usuarios.destroy', $cliente));

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $cliente->id,
        ]);
    }

    public function test_admin_cannot_block_or_delete_self(): void
    {
        $admin = User::factory()->create(['rol' => 'admin', 'estado' => 'activo']);

        $bloquearResponse = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.usuarios.estado.update', $admin), [
            'estado' => 'inactivo',
        ]);
        $bloquearResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'estado' => 'activo',
        ]);

        $eliminarResponse = $this->actingAs($admin)->delete(route('admin.PanelAdministrativo.usuarios.destroy', $admin));
        $eliminarResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_non_admin_cannot_manage_users_from_admin_panel(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);
        $otro = User::factory()->create(['rol' => 'cliente', 'estado' => 'activo']);

        $bloquearResponse = $this->actingAs($cliente)->patch(route('admin.PanelAdministrativo.usuarios.estado.update', $otro), [
            'estado' => 'inactivo',
        ]);
        $bloquearResponse->assertForbidden();

        $eliminarResponse = $this->actingAs($cliente)->delete(route('admin.PanelAdministrativo.usuarios.destroy', $otro));
        $eliminarResponse->assertForbidden();
    }

    public function test_admin_can_delete_tipo_propiedad_without_associated_publications(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $tipo = TipoPropiedad::query()->create(['nombre' => 'Campestre']);

        $response = $this->actingAs($admin)->delete(route('admin.PanelAdministrativo.tipos.destroy', $tipo));

        $response->assertRedirect(route('admin.PanelAdministrativo'));
        $this->assertDatabaseMissing('tipos_propiedad', [
            'id' => $tipo->id,
        ]);
    }

    public function test_admin_can_create_and_update_user_from_admin_panel(): void
    {
        $admin = User::factory()->create(['rol' => 'admin', 'estado' => 'activo']);

        $storeResponse = $this->actingAs($admin)->post(route('admin.PanelAdministrativo.usuarios.store'), [
            'name' => 'Carlos',
            'apellidos' => 'Rivera',
            'email' => 'carlos@example.com',
            'password' => 'password123',
            'rol' => 'cliente',
            'estado' => 'activo',
            'tipo_persona' => 'natural',
        ]);

        $storeResponse->assertRedirect(route('admin.PanelAdministrativo.usuarios.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'carlos@example.com',
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);

        $usuario = User::query()->where('email', 'carlos@example.com')->firstOrFail();

        $updateResponse = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.usuarios.update', $usuario), [
            'name' => 'Carlos Modificado',
            'apellidos' => 'Rivera',
            'email' => 'carlos.mod@example.com',
            'password' => '',
            'rol' => 'agente',
            'estado' => 'activo',
            'tipo_persona' => 'natural',
        ]);

        $updateResponse->assertRedirect(route('admin.PanelAdministrativo.usuarios.index'));
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'name' => 'Carlos Modificado',
            'email' => 'carlos.mod@example.com',
            'rol' => 'agente',
        ]);
    }

    public function test_admin_can_update_support_terms_content_from_admin_panel(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $storageDir = storage_path('app/support-pages');
        File::deleteDirectory($storageDir);

        $response = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.soporte.update'), [
            'support_slug' => 'terminos-condiciones',
            'title' => 'Terminos actualizados',
            'summary' => 'Resumen nuevo de terminos del portal.',
            'updated_at' => '2026-05-07',
            'sections_text' => "## Uso del portal\nTexto de prueba.\n- Bullet uno\n\n## Pagos\nNo hay pagos directos en el portal.",
            'return_to' => 'support',
        ]);

        $response->assertRedirect(route('admin.PanelAdministrativo.soporte', ['support_page' => 'terminos-condiciones']));

        $filePath = storage_path('app/support-pages/terminos-condiciones.json');
        $this->assertFileExists($filePath);

        $decoded = json_decode((string) file_get_contents($filePath), true);
        $this->assertIsArray($decoded);
        $this->assertSame('Terminos actualizados', $decoded['title'] ?? null);
        $this->assertSame('terminos-condiciones', $decoded['slug'] ?? null);
        $this->assertNotEmpty($decoded['sections'] ?? []);

        File::deleteDirectory($storageDir);
    }

    public function test_non_admin_cannot_update_support_terms_content(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->patch(route('admin.PanelAdministrativo.soporte.update'), [
            'support_slug' => 'terminos-condiciones',
            'title' => 'No permitido',
            'summary' => 'No permitido',
            'updated_at' => '2026-05-07',
            'sections_text' => "## Prueba\nTexto",
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_view_support_editor_page(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.PanelAdministrativo.soporte'));

        $response->assertOk();
        $response->assertSee('Terminos y soporte del portal');
    }

    public function test_non_admin_cannot_view_support_editor_page(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->get(route('admin.PanelAdministrativo.soporte'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_suggestions_panel_and_moderate_feedback(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $feedback = ComentarioPortal::query()->create([
            'user_id' => $cliente->id,
            'puntaje' => 4,
            'comentario' => 'Comentario público de prueba.',
            'sugerencia' => 'Sugerencia privada para admin.',
            'visible' => true,
        ]);

        $indexResponse = $this->actingAs($admin)->get(route('admin.PanelAdministrativo.sugerencias.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Moderación de comentarios y sugerencias');
        $indexResponse->assertSee('Sugerencia privada para admin.');

        $hideResponse = $this->actingAs($admin)->patch(route('admin.PanelAdministrativo.sugerencias.visibilidad.update', $feedback), [
            'visible' => 0,
        ]);
        $hideResponse->assertRedirect(route('admin.PanelAdministrativo.sugerencias.index'));
        $this->assertDatabaseHas('comentarios_portal', [
            'id' => $feedback->id,
            'visible' => 0,
        ]);

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.PanelAdministrativo.sugerencias.destroy', $feedback));
        $deleteResponse->assertRedirect(route('admin.PanelAdministrativo.sugerencias.index'));
        $this->assertDatabaseMissing('comentarios_portal', [
            'id' => $feedback->id,
        ]);
    }

    public function test_non_admin_cannot_access_suggestions_panel_or_moderate_feedback(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $feedback = ComentarioPortal::query()->create([
            'user_id' => $admin->id,
            'puntaje' => 5,
            'comentario' => 'Comentario visible.',
            'sugerencia' => 'Sugerencia privada.',
            'visible' => true,
        ]);

        $panelResponse = $this->actingAs($cliente)->get(route('admin.PanelAdministrativo.sugerencias.index'));
        $panelResponse->assertForbidden();

        $hideResponse = $this->actingAs($cliente)->patch(route('admin.PanelAdministrativo.sugerencias.visibilidad.update', $feedback), [
            'visible' => 0,
        ]);
        $hideResponse->assertForbidden();

        $deleteResponse = $this->actingAs($cliente)->delete(route('admin.PanelAdministrativo.sugerencias.destroy', $feedback));
        $deleteResponse->assertForbidden();
    }
}
