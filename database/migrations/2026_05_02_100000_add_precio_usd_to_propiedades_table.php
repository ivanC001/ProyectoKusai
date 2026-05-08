<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table): void {
            $table->decimal('precio_usd', 12, 2)->nullable()->after('precio');
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table): void {
            $table->dropColumn('precio_usd');
        });
    }
};
