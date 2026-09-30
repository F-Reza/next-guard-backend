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
        Schema::table('payments', function (Blueprint $table) {

            $table->string('provider')
                ->nullable()
                ->after('gateway');


            $table->string('provider_transaction_id')
                ->nullable()
                ->after('transaction_id');


            $table->json('gateway_response')
                ->nullable();


            $table->timestamp('verified_at')
                ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
