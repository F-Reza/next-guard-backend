<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('license_codes', function (Blueprint $table) {


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | License Code
            |--------------------------------------------------------------------------
            */

            $table->string(
                'code',
                100
            )->unique();



            /*
            |--------------------------------------------------------------------------
            | Subscription Plan
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'subscription_plan_id'
            )
            ->constrained('subscription_plans')
            ->cascadeOnUpdate()
            ->restrictOnDelete();



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
            |
            | active
            | used
            | expired
            | disabled
            |
            */

            $table->string(
                'status',
                30
            )
            ->default('active');



            /*
            |--------------------------------------------------------------------------
            | Usage Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId(
                'used_by'
            )
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();



            $table->foreignId(
                'used_device_id'
            )
            ->nullable()
            ->constrained('devices')
            ->nullOnDelete();



            $table->timestamp(
                'used_at'
            )
            ->nullable();



            $table->timestamps();



            $table->index([
                'status',
                'code'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists('license_codes');

    }

};