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
        Schema::create('verificaciones_usuario', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('dni', 20);
            $table->string('dni_frontal_path');
            $table->string('dni_reverso_path');
            $table->boolean('dni_legible')->default(false);
            $table->boolean('datos_coinciden')->default(false);
            $table->boolean('contacto_validado')->default(false);
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->foreignId('verificado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_verificacion')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verificaciones_usuario');
    }
};

