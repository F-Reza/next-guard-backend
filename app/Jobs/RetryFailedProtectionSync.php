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


        $processed = 0;

        $found = 0;



        ProtectionSyncLog::retryable()

            ->where(function($q){

                $q->whereNull('last_retry_at')
                ->orWhere(
                    'last_retry_at',
                    '<',
                    now()->subMinutes(5)
                );

            })

            ->orderBy('id')

            ->chunkById(100, function($logs) use(
                &$processed,
                &$found
            ){


                $found += $logs->count();



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



            });



        Log::info(
            'Protection retry job executed',
            [

                'found'=>$found,

                'processed'=>$processed

            ]
        );


    }


}