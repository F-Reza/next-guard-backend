<?php

namespace App\Services;


use App\Models\ProtectionSyncLog;
use Illuminate\Support\Facades\DB;



class ProtectionRetryService
{


    /**
     * Retry failed protection sync
     */
    public static function retry(
        ProtectionSyncLog $log
    ): array
    {


        return DB::transaction(function() use($log){


            /*
            |--------------------------------------------------------------------------
            | Validate Failed State
            |--------------------------------------------------------------------------
            */


            if(
                $log->apply_status !== 'failed'
            ){

                return [

                    'retry'=>false,

                    'message'=>'Sync is not failed.'

                ];

            }





            /*
            |--------------------------------------------------------------------------
            | Max Retry Check
            |--------------------------------------------------------------------------
            */


            if(
                $log->retry_count >= $log->max_retry
            ){

                return [

                    'retry'=>false,

                    'message'=>'Maximum retry reached.'

                ];

            }






            /*
            |--------------------------------------------------------------------------
            | Move Back To Pending
            |--------------------------------------------------------------------------
            */


            $log->update([


                'apply_status'=>'pending',


                'retry_count'=>
                    $log->retry_count + 1,


                'last_retry_at'=>now(),


                'failure_reason'=>null,


            ]);





            $log->refresh();





            return [

                'retry'=>true,


                'retry_count'=>$log->retry_count,


                'max_retry'=>$log->max_retry,


                'status'=>$log->apply_status,


                'rules_hash'=>$log->rules_hash,


            ];



        });


    }



}