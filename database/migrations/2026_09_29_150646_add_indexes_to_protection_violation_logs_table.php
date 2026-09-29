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
        Schema::table('protection_violation_logs', function (Blueprint $table) {

            $table->index([
                'device_id',
                'created_at'
            ]);

            $table->index([
                'device_id',
                'category'
            ]);

            $table->index([
                'device_id',
                'action'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protection_violation_logs', function (Blueprint $table) {
            //
        });
    }
};
