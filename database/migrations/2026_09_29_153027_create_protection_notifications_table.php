<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('protection_notifications', function (Blueprint $table) {


            $table->id();


            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();



            $table->string('type')
                ->default('protection_alert');



            $table->string('title');



            $table->text('message');



            $table->string('domain')
                ->nullable();



            $table->string('category')
                ->nullable();



            $table->timestamp('read_at')
                ->nullable();



            $table->timestamps();


            $table->index([
                'device_id',
                'created_at'
            ]);


            $table->index([
                'user_id',
                'read_at'
            ]);


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'protection_notifications'
        );

    }

};