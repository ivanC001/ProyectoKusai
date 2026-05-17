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
        Schema::create('verificaciones_propiedad', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('propiedad_id')
                ->unique()
                ->constrained('propiedades')
                ->cascadeOnDelete();
            $table->boolean('documentos_revisados')->default(false);
            $table->boolean('visita_confirmada')->default(false);
            $table->boolean('vendedor_identificado')->default(false);
            $table->foreignId('verificado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('fecha_verificacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verificaciones_propiedad');
    }
};

