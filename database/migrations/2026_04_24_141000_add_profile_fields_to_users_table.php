<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo_persona', ['natural', 'empresa'])->default('natural')->after('rol');
            $table->string('apellidos')->nullable()->after('name');
            $table->string('empresa')->nullable()->after('apellidos');
            $table->string('nombre_comercial')->nullable()->after('empresa');
            $table->string('dni', 20)->nullable()->unique()->after('email');
            $table->string('ruc', 20)->nullable()->unique()->after('dni');
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('foto_perfil')->nullable()->after('direccion');
            $table->text('descripcion')->nullable()->after('foto_perfil');
            $table->string('whatsapp', 30)->nullable()->after('telefono');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('tipo_persona');
            $table->timestamp('ultimo_login')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_persona',
                'apellidos',
                'empresa',
                'nombre_comercial',
                'dni',
                'ruc',
                'direccion',
                'foto_perfil',
                'descripcion',
                'whatsapp',
                'estado',
                'ultimo_login',
            ]);
        });
    }
};
