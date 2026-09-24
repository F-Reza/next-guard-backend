<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trial_entitlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->string('status', 20)
                ->default('eligible');

            $table->boolean('base_trial')
                ->default(true);

            $table->timestamps();

            $table->unique(
                ['user_id', 'device_id'],
                'trial_entitlements_user_device_unique'
            );

            $table->index(['user_id', 'status']);
            $table->index(['device_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_entitlements');
    }
};
