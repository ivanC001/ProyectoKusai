<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contactos', function (Blueprint $table): void {
            $table->foreignId('user_id')
                ->nullable()
                ->after('propiedad_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->unique(['user_id', 'propiedad_id'], 'contactos_user_propiedad_unique');
        });
    }

    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table): void {
            $table->dropUnique('contactos_user_propiedad_unique');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
