<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas_propiedad', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedades')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('puntaje');
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->unique(['propiedad_id', 'user_id'], 'resena_unica_por_usuario_propiedad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas_propiedad');
    }
};
