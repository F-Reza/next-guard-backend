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
        Schema::create('protection_violation_logs', function (Blueprint $table) {

            $table->id();


            $table->foreignId('device_id')
                ->constrained()
                ->cascadeOnDelete();


            $table->foreignId('rule_id')
                ->nullable()
                ->constrained('protection_rules')
                ->nullOnDelete();


            $table->string('domain');


            $table->string('category')
                ->nullable();


            $table->string('action')
                ->default('blocked');


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protection_violation_logs');
    }
};
