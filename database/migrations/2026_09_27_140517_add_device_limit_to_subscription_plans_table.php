<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::table(
            'subscription_plans',
            function(Blueprint $table){

                $table->unsignedInteger('device_limit')
                    ->default(1)
                    ->after('price');

            }
        );

    }



    public function down(): void
    {

        Schema::table(
            'subscription_plans',
            function(Blueprint $table){

                $table->dropColumn(
                    'device_limit'
                );

            }
        );

    }

};