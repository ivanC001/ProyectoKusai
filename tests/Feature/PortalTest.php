<?php

namespace Tests\Feature;

use App\Models\Favorito;
use App\Models\ImagenPropiedad;
use App\Models\ComentarioPortal;
use App\Models\Propiedad;
use App\Models\ResenaPropiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\User;
use App\Models\VerificacionPropiedad;
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

    public function test_how_to_publish_page_can_be_rendered(): void
    {
        $response = $this->get(route('portal.como-publicar'));

        $response->assertOk();
        $response->assertSee('Cómo publicar tu propiedad en Kusay.pe', false);
    }

    public function test_authenticated_user_can_leave_feedback_in_how_to_publish_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('portal.como-publicar.comentarios.store'), [
            'puntaje' => 5,
            'comentario' => 'El flujo para publicar me parecio claro y facil de seguir.',
            'sugerencia' => 'Podrian agregar una mini guia en video para nuevos usuarios.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('portal_feedback_success');

        $this->assertDatabaseHas('comentarios_portal', [
            'user_id' => $user->id,
            'puntaje' => 5,
            'comentario' => 'El flujo para publicar me parecio claro y facil de seguir.',
            'sugerencia' => 'Podrian agregar una mini guia en video para nuevos usuarios.',
        ]);
    }

    public function test_guest_cannot_leave_feedback_in_how_to_publish_page(): void
    {
        $response = $this->post(route('portal.como-publicar.comentarios.store'), [
            'puntaje' => 4,
            'comentario' => 'Muy buena experiencia publicando.',
            'sugerencia' => 'Agregar mas ejemplos de fotos.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comentarios_portal', 0);
    }

    public function test_how_to_publish_page_hides_admin_only_suggestion_text(): void
    {
        $user = User::factory()->create();

        ComentarioPortal::query()->create([
            'user_id' => $user->id,
            'puntaje' => 5,
            'comentario' => 'Excelente experiencia de publicación.',
            'sugerencia' => 'Texto privado solo para admin.',
            'visible' => true,
        ]);

        $response = $this->get(route('portal.como-publicar'));

        $response->assertOk();
        $response->assertSee('Excelente experiencia de publicación.');
        $response->assertDontSee('Texto privado solo para admin.');
    }

    public function test_home_shows_separated_blocks_for_sale_rent_and_projects(): void
    {
        [$ventaCasa, $alquilerDepartamento, , $proyecto] = $this->seedPropiedades();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Propiedades en venta');
        $response->assertSee('Propiedades en alquiler');
        $response->assertSee('Proyectos inmobiliarios');
        $response->assertSee($ventaCasa->titulo);
        $response->assertSee($alquilerDepartamento->titulo);
        $response->assertSee($proyecto->titulo);
    }

    public function test_home_prioritizes_verified_properties_at_the_top(): void
    {
        [$ventaCasa] = $this->seedPropiedades();

        $ventaSinVerificar = Propiedad::query()->create([
            'titulo' => 'Casa nueva sin verificar',
            'descripcion' => 'Casa recientemente publicada aun sin verificacion.',
            'precio' => 160000,
            'tipo' => 'venta',
            'estado' => 'disponible',
            'direccion' => 'Jr. Reciente 888',
            'habitaciones' => 3,
            'banos' => 2,
            'area' => 150,
            'user_id' => $ventaCasa->user_id,
            'tipo_propiedad_id' => $ventaCasa->tipo_propiedad_id,
            'ubicacion_id' => $ventaCasa->ubicacion_id,
        ]);

        VerificacionPropiedad::query()->create([
            'propiedad_id' => $ventaCasa->id,
            'documentos_revisados' => true,
            'visita_confirmada' => true,
            'vendedor_identificado' => true,
            'verificado_por' => null,
            'fecha_verificacion' => now(),
        ]);

        $ventaCasa->forceFill(['created_at' => now()->subDays(2)])->save();
        $ventaSinVerificar->forceFill(['created_at' => now()])->save();

        $response = $this->get(route('home'));
        $response->assertOk();

        $contenido = $response->getContent();
        $this->assertIsString($contenido);

        $posVerificada = strpos($contenido, $ventaCasa->titulo);
        $posSinVerificar = strpos($contenido, $ventaSinVerificar->titulo);

        $this->assertNotFalse($posVerificada);
        $this->assertNotFalse($posSinVerificar);
        $this->assertLessThan($posSinVerificar, $posVerificada);
    }

    public function test_home_registers_unique_portal_visit_per_session(): void
    {
        $this->seedPropiedades();

        $firstResponse = $this->get(route('home'));
        $firstResponse->assertOk();
        $this->assertDatabaseCount('portal_visitas', 1);

        $secondResponse = $this->get(route('home'));
        $secondResponse->assertOk();
        $this->assertDatabaseCount('portal_visitas', 1);
    }

    public function test_home_filters_by_operation_and_type(): void
    {
        [$ventaCasa, $alquilerDepartamento] = $this->seedPropiedades();

        $response = $this->get(route('home', [
            'modo' => 'alquilar',
            'tipo_propiedad_id' => $alquilerDepartamento->tipo_propiedad_id,
        ]));

        $response->assertOk();
        $response->assertSee($alquilerDepartamento->titulo);
        $response->assertDontSee($ventaCasa->titulo);
    }

    public function test_home_filters_projects_mode_using_project_property_type(): void
    {
        [$ventaCasa, $alquilerDepartamento, , $proyecto] = $this->seedPropiedades();

        $response = $this->get(route('home', [
            'modo' => 'proyectos',
        ]));

        $response->assertOk();
        $response->assertSee($proyecto->titulo);
        $response->assertDontSee($ventaCasa->titulo);
        $response->assertDontSee($alquilerDepartamento->titulo);
    }

    public function test_authenticated_user_can_filter_home_by_favorites(): void
    {
        [$ventaCasa, $alquilerDepartamento] = $this->seedPropiedades();
        $user = User::factory()->create();
        $this->actingAs($user);

        Favorito::query()->create([
            'user_id' => $user->id,
            'propiedad_id' => $alquilerDepartamento->id,
        ]);

        $response = $this->get(route('home', ['favoritos' => 1]));

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

    public function test_buyer_can_send_contact_request_to_property_owner(): void
    {
        config(['mail.default' => 'array']);
        [$propiedadVisible] = $this->seedPropiedades();
        $comprador = User::factory()->create([
            'name' => 'Comprador',
            'apellidos' => 'Prueba',
            'email' => 'comprador@example.com',
        ]);

        $response = $this->actingAs($comprador)->post(route('portal.propiedades.contacto', $propiedadVisible), [
            'nombre' => 'Comprador Interesado',
            'email' => 'comprador@example.com',
            'telefono' => '999888777',
            'mensaje' => 'Hola, deseo recibir mas informacion de esta propiedad.',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('contacto_success');

        $this->assertDatabaseHas('contactos', [
            'propiedad_id' => $propiedadVisible->id,
            'user_id' => $comprador->id,
            'nombre' => 'Comprador Interesado',
            'email' => 'comprador@example.com',
            'telefono' => '999888777',
            'mensaje' => 'Hola, deseo recibir mas informacion de esta propiedad.',
        ]);
    }

    public function test_guest_cannot_send_contact_request(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();

        $response = $this->post(route('portal.propiedades.contacto', $propiedadVisible), [
            'nombre' => 'Invitado',
            'email' => 'invitado@example.com',
            'mensaje' => 'Quiero informacion de la propiedad.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('contactos', 0);
    }

    public function test_user_cannot_send_duplicate_contact_request_for_same_property(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();
        $comprador = User::factory()->create([
            'name' => 'Comprador',
            'apellidos' => 'Duplicado',
            'email' => 'comprador.dup@example.com',
        ]);

        $payload = [
            'nombre' => 'Comprador Unico',
            'email' => 'comprador.dup@example.com',
            'telefono' => '998887776',
            'mensaje' => 'Hola, me interesa esta propiedad para compra.',
        ];

        $this->actingAs($comprador)->post(route('portal.propiedades.contacto', $propiedadVisible), $payload)
            ->assertRedirect();

        $this->actingAs($comprador)->post(route('portal.propiedades.contacto', $propiedadVisible), $payload)
            ->assertRedirect()
            ->assertSessionHas('contacto_info');

        $this->assertDatabaseCount('contactos', 1);
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

    public function test_authenticated_user_can_publish_comment_on_property(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('portal.propiedades.show', $propiedadVisible))
            ->post(route('portal.propiedades.comentarios.store', $propiedadVisible), [
                'puntaje' => 4,
                'mensaje' => 'Me interesa esta propiedad, quisiera saber si acepta visita esta semana.',
            ]);

        $response->assertRedirect(route('portal.propiedades.show', $propiedadVisible));
        $response->assertSessionHas('comentario_success');

        $this->assertDatabaseHas('comentarios_propiedad', [
            'propiedad_id' => $propiedadVisible->id,
            'user_id' => $user->id,
            'puntaje' => 4,
            'mensaje' => 'Me interesa esta propiedad, quisiera saber si acepta visita esta semana.',
        ]);
    }

    public function test_authenticated_user_can_create_or_update_review_on_property(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();
        $user = User::factory()->create();

        $crearResponse = $this->actingAs($user)
            ->from(route('portal.propiedades.show', $propiedadVisible))
            ->post(route('portal.propiedades.resenas.store', $propiedadVisible), [
                'puntaje' => 5,
                'comentario' => 'Excelente ubicacion y buena atencion del anunciante.',
            ]);

        $crearResponse->assertRedirect(route('portal.propiedades.show', $propiedadVisible));
        $crearResponse->assertSessionHas('resena_success');

        $this->assertDatabaseHas('resenas_propiedad', [
            'propiedad_id' => $propiedadVisible->id,
            'user_id' => $user->id,
            'puntaje' => 5,
        ]);

        $actualizarResponse = $this->actingAs($user)
            ->from(route('portal.propiedades.show', $propiedadVisible))
            ->post(route('portal.propiedades.resenas.store', $propiedadVisible), [
                'puntaje' => 3,
                'comentario' => 'Actualizo mi experiencia despues de la visita.',
            ]);

        $actualizarResponse->assertRedirect(route('portal.propiedades.show', $propiedadVisible));
        $actualizarResponse->assertSessionHas('resena_success');

        $this->assertDatabaseHas('resenas_propiedad', [
            'propiedad_id' => $propiedadVisible->id,
            'user_id' => $user->id,
            'puntaje' => 3,
        ]);
        $this->assertSame(1, ResenaPropiedad::query()->count());
    }

    public function test_guest_cannot_publish_review_on_property(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();

        $response = $this->post(route('portal.propiedades.resenas.store', $propiedadVisible), [
            'puntaje' => 4,
            'comentario' => 'Muy buena opcion de compra.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('resenas_propiedad', 0);
    }

    public function test_guest_cannot_toggle_property_favorite(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();

        $response = $this->postJson(route('portal.propiedades.favoritos.toggle', $propiedadVisible));

        $response->assertUnauthorized();
        $this->assertDatabaseCount('favoritos', 0);
    }

    public function test_unverified_user_cannot_toggle_property_favorite(): void
    {
        [$propiedadVisible] = $this->seedPropiedades();
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->postJson(route('portal.propiedades.favoritos.toggle', $propiedadVisible));

        $response->assertForbidden();
        $response->assertJson([
            'ok' => false,
        ]);
        $this->assertDatabaseCount('favoritos', 0);
    }

    /**
     * @return array{0: Propiedad, 1: Propiedad, 2: Propiedad, 3: Propiedad}
     */
    private function seedPropiedades(): array
    {
        $user = User::factory()->create();
        $tipoCasa = TipoPropiedad::query()->create(['nombre' => 'Casa']);
        $tipoDepartamento = TipoPropiedad::query()->create(['nombre' => 'Departamento']);
        $tipoProyecto = TipoPropiedad::query()->create(['nombre' => 'Proyecto inmobiliario']);
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

        $proyecto = Propiedad::query()->create([
            'titulo' => 'Condominio Valle Verde',
            'descripcion' => 'Proyecto inmobiliario con areas comunes y etapas de preventa activas.',
            'precio' => 90000,
            'tipo' => 'venta',
            'estado' => 'disponible',
            'direccion' => 'Av. Proyecto 250',
            'habitaciones' => 2,
            'banos' => 2,
            'area' => 95,
            'user_id' => $user->id,
            'tipo_propiedad_id' => $tipoProyecto->id,
            'ubicacion_id' => $ubicacion->id,
        ]);

        return [$ventaCasa, $alquilerDepartamento, $noDisponible, $proyecto];
    }
}
