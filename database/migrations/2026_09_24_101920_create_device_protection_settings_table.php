<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_protection_settings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Protection Features
            |--------------------------------------------------------------------------
            */

            $table->boolean('betting_block')
                ->default(false);

            $table->boolean('adult_content_block')
                ->default(false);

            $table->boolean('facebook_ad_block')
                ->default(false);

            $table->boolean('youtube_ad_block')
                ->default(false);

            $table->boolean('safe_search')
                ->default(false);

            $table->boolean('dns_protection')
                ->default(false);


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('protection_status', 30)
                ->default('inactive');


            /*
            |--------------------------------------------------------------------------
            | Sync Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_sync_at')
                ->nullable();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | One settings per device
            |--------------------------------------------------------------------------
            */

            $table->unique('device_id');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('device_protection_settings');
    }
};