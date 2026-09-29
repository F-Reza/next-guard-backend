<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use App\Models\ProtectionSyncLog;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;



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

            ->where(function($q) use($timeoutMinutes){


                $q->whereNull(
                    'last_retry_at'
                )

                ->orWhere(
                    'last_retry_at',
                    '<',
                    Carbon::now()
                        ->subMinutes($timeoutMinutes)
                );


            })

            ->orderBy('id')

            ->chunkById(
                100,
                function($logs) use(&$count){


                    foreach($logs as $log){


                        DB::transaction(function() use(
                            $log,
                            &$count
                        ){


                            $log->refresh();



                            /*
                            |--------------------------------------------------------------------------
                            | Ignore if already changed
                            |--------------------------------------------------------------------------
                            */


                            if(
                                $log->apply_status !== 'pending'
                            ){

                                return;

                            }





                            /*
                            |--------------------------------------------------------------------------
                            | Maximum Retry Reached
                            |--------------------------------------------------------------------------
                            */


                            if(
                                $log->retry_count >= $log->max_retry
                            ){


                                $log->update([

                                    'apply_status'=>'failed',

                                    'failure_reason'=>
                                        'Maximum retry reached.',

                                    'last_retry_at'=>now(),

                                ]);



                                $count++;


                                $this->warn(

                                    "Sync #"
                                    .$log->id
                                    ." maximum retry reached."

                                );


                                return;


                            }







                            /*
                            |--------------------------------------------------------------------------
                            | ACK Timeout
                            |--------------------------------------------------------------------------
                            */


                            $log->update([


                                'apply_status'=>'failed',


                                'failure_reason'=>
                                    'Device ACK timeout.',



                                'retry_count'=>
                                    min(

                                        $log->retry_count + 1,

                                        $log->max_retry

                                    ),



                                'last_retry_at'=>null,


                            ]);




                            $count++;




                            $this->warn(

                                "Sync #"
                                .$log->id
                                ." marked failed."

                            );



                        });



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