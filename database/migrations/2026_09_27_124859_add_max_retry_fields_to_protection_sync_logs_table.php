<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::table(
            'protection_sync_logs',
            function(Blueprint $table){

                $table->unsignedInteger('max_retry')
                    ->default(3)
                    ->after('retry_count');


                $table->timestamp('last_retry_at')
                    ->nullable()
                    ->after('failure_reason');

            }
        );

    }



    public function down(): void
    {

        Schema::table(
            'protection_sync_logs',
            function(Blueprint $table){

                $table->dropColumn([
                    'max_retry',
                    'last_retry_at'
                ]);

            }
        );

    }

};