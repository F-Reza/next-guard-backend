<?php

namespace App\Jobs;


use App\Models\ProtectionSyncLog;
use App\Services\ProtectionRetryService;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;



class RetryFailedProtectionSync implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;



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
            ->orderBy(
                'id'
            )
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