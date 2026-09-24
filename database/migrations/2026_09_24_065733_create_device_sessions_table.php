<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_sessions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('refresh_token_hash', 64);

            $table->string('status', 20)
                ->default('active');

            $table->timestamp('expires_at');

            $table->timestamp('last_used_at')
                ->nullable();

            $table->timestamp('revoked_at')
                ->nullable();

            $table->string('ip_address', 45)
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            $table->timestamps();

            $table->index([
                'user_id',
                'status'
            ]);

            $table->index([
                'device_id',
                'status'
            ]);

            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_sessions');
    }
};