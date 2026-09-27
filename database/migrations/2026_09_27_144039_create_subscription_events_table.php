<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('subscription_events', function(Blueprint $table){

            $table->id();


            $table->foreignId(
                'subscription_id'
            )
            ->constrained()
            ->cascadeOnDelete();



            $table->string(
                'event'
            );



            $table->string(
                'old_status'
            )
            ->nullable();



            $table->string(
                'new_status'
            )
            ->nullable();



            $table->text(
                'description'
            )
            ->nullable();



            $table->timestamps();


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'subscription_events'
        );

    }

};