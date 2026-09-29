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
        Schema::table(
            'protection_sync_logs',
            function(Blueprint $table){

                $table->index([
                    'device_id',
                    'apply_status'
                ]);

                $table->index([
                    'device_id',
                    'sync_version'
                ]);

                $table->unique([
                    'device_id',
                    'sync_version'
                ]);

            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protection_sync_logs', function (Blueprint $table) {
            //
        });
    }
};
