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
        Schema::create('trial_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();

            $table->string('event_type', 50);

            $table->timestamp('old_expires_at')->nullable();

            $table->timestamp('new_expires_at')->nullable();

            $table->text('reason')->nullable();

            // Admin system will be added later.
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'event_type']);
            $table->index(['device_id', 'event_type']);
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_events');
    }
};