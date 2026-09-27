<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{


    public function up(): void
    {


        Schema::create('subscription_invoices', function(Blueprint $table){


            $table->id();


            $table->foreignId(
                'user_id'
            )
            ->constrained()
            ->cascadeOnDelete();



            $table->foreignId(
                'subscription_id'
            )
            ->constrained()
            ->cascadeOnDelete();



            $table->string(
                'invoice_no'
            )
            ->unique();



            $table->decimal(
                'amount',
                10,
                2
            );



            $table->string(
                'currency'
            )
            ->default('USD');



            $table->enum(
                'status',
                [
                    'paid',
                    'unpaid',
                    'cancelled'
                ]
            )
            ->default('paid');



            $table->timestamps();


        });


    }



    public function down(): void
    {

        Schema::dropIfExists(
            'subscription_invoices'
        );

    }


};