<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {


            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Plan Information
            |--------------------------------------------------------------------------
            */

            $table->string('name', 100);


            $table->text('description')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'price',
                10,
                2
            )->default(0);



            $table->string(
                'currency',
                10
            )->default('BDT');



            /*
            |--------------------------------------------------------------------------
            | Duration
            |--------------------------------------------------------------------------
            */

            $table->integer(
                'duration_days'
            );



            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string(
                'status',
                20
            )->default('active');



            /*
            |--------------------------------------------------------------------------
            | Features
            |--------------------------------------------------------------------------
            */

            $table->json(
                'features'
            )->nullable();



            $table->timestamps();



            $table->index([
                'status'
            ]);

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }

};