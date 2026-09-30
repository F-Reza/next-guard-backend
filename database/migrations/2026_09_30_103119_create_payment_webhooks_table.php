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
        Schema::create('payment_webhooks', function (Blueprint $table) {

            $table->id();

            $table->string('gateway');

            $table->string('event_id')
                ->unique();

            $table->string('transaction_id')
                ->nullable();

            $table->json('payload');

            $table->string('status')
                ->default('pending');

            $table->timestamp('processed_at')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_webhooks');
    }
};
