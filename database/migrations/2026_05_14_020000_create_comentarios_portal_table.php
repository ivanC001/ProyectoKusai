<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios_portal', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('puntaje');
            $table->text('comentario');
            $table->text('sugerencia')->nullable();
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->unique('user_id', 'comentario_portal_unico_por_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios_portal');
    }
};
