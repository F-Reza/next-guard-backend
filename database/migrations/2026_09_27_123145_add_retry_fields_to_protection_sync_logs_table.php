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

                $table->unsignedInteger('retry_count')
                    ->default(0)
                    ->after('apply_status');


                $table->text('failure_reason')
                    ->nullable()
                    ->after('retry_count');

            }
        );

    }



    public function down(): void
    {

        Schema::table(
            'protection_sync_logs',
            function(Blueprint $table){

                $table->dropColumn([
                    'retry_count',
                    'failure_reason'
                ]);

            }
        );

    }

};