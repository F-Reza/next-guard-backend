<?php

namespace App\Jobs;


use App\Models\ProtectionSyncLog;
use App\Services\ProtectionRetryService;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use Illuminate\Support\Facades\Log;



class RetryFailedProtectionSync implements ShouldQueue, ShouldBeUnique
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

        public $uniqueFor = 300;

    /**
     * Retry failed protection sync
     */
    public function handle(): void
    {



        $logs = ProtectionSyncLog::where(
                'apply_status',
                'failed'
            )
            ->whereColumn(
                'retry_count',
                '<',
                'max_retry'
            )
            ->where(function($q){

                $q->whereNull('last_retry_at')
                ->orWhere(
                    'last_retry_at',
                    '<',
                    now()->subMinutes(5)
                );

            })
            ->orderBy('id')
            ->get();



        $processed = 0;





        foreach($logs as $log){


            $result = ProtectionRetryService::retry(
                $log
            );



            if(
                $result['retry'] ?? false
            ){

                $processed++;

            }



        }





        Log::info(
            'Protection retry job executed',
            [

                'found'=>$logs->count(),

                'processed'=>$processed

            ]
        );



    }



}