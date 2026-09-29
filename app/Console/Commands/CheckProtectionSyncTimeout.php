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


        $count = 0;



        ProtectionSyncLog::where(
                'apply_status',
                'pending'
            )
            ->whereColumn(
                'retry_count',
                '<',
                'max_retry'
            )
            ->where(
                'created_at',
                '<',
                Carbon::now()
                    ->subMinutes($timeoutMinutes)
            )
            ->orderBy('id')
            ->chunkById(
                100,
                function($logs) use(&$count){


                    foreach($logs as $log){



                        $log->update([


                            'apply_status'=>'failed',


                            'failure_reason'=>
                                'Device ACK timeout.',


                            'last_retry_at'=>null,


                        ]);





                        $count++;



                        $this->warn(

                            "Sync #"
                            .$log->id
                            ." marked failed."

                        );



                    }



                }
            );






        if($count === 0){


            $this->info(

                'No timed out sync found.'

            );


            return Command::SUCCESS;


        }





        $this->info(

            'Timeout processed: '
            .$count

        );



        return Command::SUCCESS;



    }


}