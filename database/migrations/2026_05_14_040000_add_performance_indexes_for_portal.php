<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table): void {
            $table->index(['estado', 'tipo', 'created_at'], 'propiedades_estado_tipo_created_idx');
            $table->index(['estado', 'tipo_propiedad_id', 'created_at'], 'propiedades_estado_tipo_prop_created_idx');
            $table->index(['ubicacion_id', 'estado'], 'propiedades_ubicacion_estado_idx');
            $table->index('precio', 'propiedades_precio_idx');
            $table->index('area', 'propiedades_area_idx');
        });

        Schema::table('ubicaciones', function (Blueprint $table): void {
            $table->index(['distrito', 'departamento'], 'ubicaciones_distrito_departamento_idx');
            $table->index(['departamento', 'provincia', 'distrito'], 'ubicaciones_dep_prov_dist_idx');
        });

        Schema::table('contactos', function (Blueprint $table): void {
            $table->index(['propiedad_id', 'created_at'], 'contactos_propiedad_created_idx');
            $table->index(['user_id', 'created_at'], 'contactos_user_created_idx');
        });

        Schema::table('favoritos', function (Blueprint $table): void {
            $table->index('propiedad_id', 'favoritos_propiedad_idx');
        });

        Schema::table('visitas', function (Blueprint $table): void {
            $table->index(['propiedad_id', 'fecha_visita'], 'visitas_propiedad_fecha_idx');
        });

        Schema::table('comentarios_propiedad', function (Blueprint $table): void {
            $table->index(['propiedad_id', 'created_at'], 'comentarios_propiedad_propiedad_created_idx');
        });

        Schema::table('resenas_propiedad', function (Blueprint $table): void {
            $table->index(['propiedad_id', 'created_at'], 'resenas_propiedad_propiedad_created_idx');
        });

        Schema::table('portal_visitas', function (Blueprint $table): void {
            $table->index(['visitor_key', 'fecha_visita'], 'portal_visitas_visitor_fecha_idx');
        });

        Schema::table('comentarios_portal', function (Blueprint $table): void {
            $table->index(['visible', 'created_at'], 'comentarios_portal_visible_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('comentarios_portal', function (Blueprint $table): void {
            $table->dropIndex('comentarios_portal_visible_created_idx');
        });

        Schema::table('portal_visitas', function (Blueprint $table): void {
            $table->dropIndex('portal_visitas_visitor_fecha_idx');
        });

        Schema::table('visitas', function (Blueprint $table): void {
            $table->dropIndex('visitas_propiedad_fecha_idx');
        });

        Schema::table('resenas_propiedad', function (Blueprint $table): void {
            $table->dropIndex('resenas_propiedad_propiedad_created_idx');
        });

        Schema::table('comentarios_propiedad', function (Blueprint $table): void {
            $table->dropIndex('comentarios_propiedad_propiedad_created_idx');
        });

        Schema::table('favoritos', function (Blueprint $table): void {
            $table->dropIndex('favoritos_propiedad_idx');
        });

        Schema::table('contactos', function (Blueprint $table): void {
            $table->dropIndex('contactos_propiedad_created_idx');
            $table->dropIndex('contactos_user_created_idx');
        });

        Schema::table('ubicaciones', function (Blueprint $table): void {
            $table->dropIndex('ubicaciones_distrito_departamento_idx');
            $table->dropIndex('ubicaciones_dep_prov_dist_idx');
        });

        Schema::table('propiedades', function (Blueprint $table): void {
            $table->dropIndex('propiedades_estado_tipo_created_idx');
            $table->dropIndex('propiedades_estado_tipo_prop_created_idx');
            $table->dropIndex('propiedades_ubicacion_estado_idx');
            $table->dropIndex('propiedades_precio_idx');
            $table->dropIndex('propiedades_area_idx');
        });
    }
};
