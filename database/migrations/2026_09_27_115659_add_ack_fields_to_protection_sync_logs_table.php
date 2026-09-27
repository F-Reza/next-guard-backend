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

                $table->string(
                    'apply_status'
                )
                ->default('pending')
                ->after('rules_hash');


                $table->timestamp(
                    'applied_at'
                )
                ->nullable()
                ->after('apply_status');


                $table->string(
                    'device_version'
                )
                ->nullable()
                ->after('applied_at');


            }
        );

    }



    public function down(): void
    {

        Schema::table(
            'protection_sync_logs',
            function(Blueprint $table){

                $table->dropColumn([
                    'apply_status',
                    'applied_at',
                    'device_version'
                ]);

            }
        );

    }

};