<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::table('admin_activity_logs', function(Blueprint $table){


            $table->string('severity')
                ->default('info')
                ->after('action');


            $table->string('device')
                ->nullable()
                ->after('user_agent');


            $table->string('browser')
                ->nullable()
                ->after('device');


            $table->string('os')
                ->nullable()
                ->after('browser');


            $table->string('session_id')
                ->nullable()
                ->after('os');


            $table->json('metadata')
                ->nullable()
                ->after('session_id');


        });

    }




    public function down(): void
    {

        Schema::table('admin_activity_logs', function(Blueprint $table){


            $table->dropColumn([

                'severity',

                'device',

                'browser',

                'os',

                'session_id',

                'metadata'

            ]);

        });

    }

};