<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('protection_sync_logs', function (Blueprint $table) {


            $table->id();


            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();



            $table->unsignedInteger('sync_version')
                ->default(1);



            $table->string('rules_hash',64);



            $table->string('ip_address')
                ->nullable();



            $table->text('user_agent')
                ->nullable();



            $table->timestamp('synced_at')
                ->nullable();



            $table->timestamps();


        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'protection_sync_logs'
        );

    }

};