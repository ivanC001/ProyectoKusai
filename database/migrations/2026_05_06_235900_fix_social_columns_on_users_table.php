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
        if (
            Schema::hasColumn('users', 'provider')
            && Schema::hasColumn('users', 'provider_id')
            && Schema::hasColumn('users', 'avatar')
        ) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('descripcion');
            }
            if (! Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
            }
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('provider_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $drop = [];

            if (Schema::hasColumn('users', 'provider')) {
                $drop[] = 'provider';
            }
            if (Schema::hasColumn('users', 'provider_id')) {
                $drop[] = 'provider_id';
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $drop[] = 'avatar';
            }

            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};

