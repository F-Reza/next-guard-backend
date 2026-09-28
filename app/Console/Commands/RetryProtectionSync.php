<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\Models\ProtectionSyncLog;

use App\Services\ProtectionRetryService;



class RetryProtectionSync extends Command
{


    protected $signature = 
        'protection:retry-sync';



    protected $description =
        'Retry failed protection sync logs';




    public function handle(): int
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
            ->get();





        if($logs->isEmpty()){


            $this->info(
                'No failed sync found.'
            );


            return Command::SUCCESS;

        }







        foreach($logs as $log){


            $result = 
                ProtectionRetryService::retry(
                    $log
                );



            if($result['retry']){


                $this->info(

                    "Retry queued: Sync #"
                    .$log->id
                    ." Device #"
                    .$log->device_id

                );


            }
            else{


                $this->warn(

                    "Skipped Sync #"
                    .$log->id
                    .": "
                    .$result['message']

                );


            }


        }





        $this->info(

            'Processed failed syncs: '
            .$logs->count()

        );




        return Command::SUCCESS;


    }


}