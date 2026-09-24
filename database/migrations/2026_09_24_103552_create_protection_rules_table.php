<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protection_rules', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Rule Category
            |--------------------------------------------------------------------------
            */

            $table->string('category', 50);


            /*
            |--------------------------------------------------------------------------
            | Domain / Pattern
            |--------------------------------------------------------------------------
            */

            $table->string('domain', 255);


            /*
            |--------------------------------------------------------------------------
            | Rule Type
            |--------------------------------------------------------------------------
            */

            $table->string('rule_type', 30)
                ->default('domain');


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30)
                ->default('active');


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            $table->text('description')
                ->nullable();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'category',
                'status'
            ]);


            $table->unique([
                'category',
                'domain'
            ]);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('protection_rules');
    }
};