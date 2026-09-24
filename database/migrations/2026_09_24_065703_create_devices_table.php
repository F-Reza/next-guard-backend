<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->char('device_uuid_hash', 64);

            $table->string('platform', 20)
                ->default('android');

            $table->string('model', 120)
                ->nullable();

            $table->string('manufacturer', 120)
                ->nullable();

            $table->string('android_version', 30)
                ->nullable();

            $table->string('app_version', 30)
                ->nullable();

            $table->string('management_mode', 20)
                ->default('standard');

            $table->string('status', 30)
                ->default('active');

            $table->timestamp('last_seen_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'user_id',
                'device_uuid_hash'
            ]);

            $table->index('last_seen_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};