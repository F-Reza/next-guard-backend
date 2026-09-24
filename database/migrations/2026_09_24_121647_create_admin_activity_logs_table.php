<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {


            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Admin
            |--------------------------------------------------------------------------
            */

            $table->foreignId('admin_id')
                ->constrained('admins')
                ->cascadeOnUpdate()
                ->restrictOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Activity
            |--------------------------------------------------------------------------
            */

            $table->string(
                'action',
                100
            );


            $table->text(
                'description'
            )->nullable();



            /*
            |--------------------------------------------------------------------------
            | Request Info
            |--------------------------------------------------------------------------
            */

            $table->string(
                'ip_address',
                45
            )->nullable();


            $table->text(
                'user_agent'
            )->nullable();



            $table->timestamps();



            $table->index([
                'admin_id',
                'created_at'
            ]);


            $table->index(
                'action'
            );


        });
    }



    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }

};