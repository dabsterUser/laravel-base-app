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
        Schema::table('settings', function (Blueprint $table) {
            try {
                $table->dropUnique('settings_key_unique');
            } catch (\Exception $e) {
                // Fallback or ignore for SQLite rebuild
            }

            $table->unique(['key', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            try {
                $table->dropUnique(['key', 'tenant_id']);
                $table->unique('key');
            } catch (\Exception $e) {
                // Fallback or ignore
            }
        });
    }
};
