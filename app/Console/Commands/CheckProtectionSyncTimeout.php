<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\Models\ProtectionSyncLog;

use Carbon\Carbon;



class CheckProtectionSyncTimeout extends Command
{


    protected $signature =
        'protection:check-timeout';



    protected $description =
        'Mark pending protection sync as failed after timeout';




    public function handle(): int
    {


        $timeoutMinutes = 5;



        $logs = ProtectionSyncLog::where(

                'apply_status',

                'pending'

            )
            ->where(

                'created_at',

                '<',

                Carbon::now()
                    ->subMinutes($timeoutMinutes)

            )
            ->get();







        if($logs->isEmpty()){


            $this->info(
                'No timed out sync found.'
            );


            return Command::SUCCESS;

        }







        foreach($logs as $log){



            $log->update([


                'apply_status'=>'failed',


                'failure_reason'=>
                    'Device ACK timeout.',


                'last_retry_at'=>null,


            ]);





            $this->warn(

                "Sync #"
                .$log->id
                ." marked failed."

            );



        }







        $this->info(

            'Timeout processed: '
            .$logs->count()

        );



        return Command::SUCCESS;


    }


}