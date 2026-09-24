<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {


            $table->id();



            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Subscription Plan
            |--------------------------------------------------------------------------
            */

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->cascadeOnUpdate()
                ->restrictOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Device
            |--------------------------------------------------------------------------
            */

            $table->foreignId('device_id')
                ->nullable()
                ->constrained('devices')
                ->cascadeOnUpdate()
                ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Subscription Period
            |--------------------------------------------------------------------------
            */

            $table->timestamp('starts_at')
                ->nullable();

            $table->timestamp('expires_at')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string(
                'status',
                30
            )->default('active');



            /*
            |--------------------------------------------------------------------------
            | Subscription Source
            |--------------------------------------------------------------------------
            |
            | payment
            | license
            | admin
            |
            */

            $table->string(
                'source',
                30
            )->default('payment');



            /*
            |--------------------------------------------------------------------------
            | External Reference
            |--------------------------------------------------------------------------
            */

            $table->string(
                'payment_reference',
                255
            )->nullable();



            // $table->foreignId('license_code_id')
            //     ->nullable()
            //     ->constrained('license_codes')
            //     ->nullOnDelete();



            $table->timestamps();



            $table->index([
                'user_id',
                'status'
            ]);


            $table->index([
                'device_id',
                'status'
            ]);


            $table->index(
                'expires_at'
            );

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }

};