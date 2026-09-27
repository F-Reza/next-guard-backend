<?php

namespace App\Console\Commands;


use App\Models\Subscription;

use App\Models\SubscriptionEvent;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;



class ExpireSubscriptions extends Command
{


    protected $signature = 'subscriptions:expire';



    protected $description =
        'Expire old subscriptions and disable protection';





    public function handle(): int
    {


        $subscriptions = Subscription::where(

                'status',

                'active'

            )
            ->where(

                'expires_at',

                '<',

                now()

            )
            ->get();







        foreach($subscriptions as $subscription){



            DB::transaction(function() use(

                $subscription

            ){



                /*
                |--------------------------------------------------------------------------
                | Update Subscription Status
                |--------------------------------------------------------------------------
                */


                $oldStatus = $subscription->status;



                $subscription->update([

                    'status'=>'expired'

                ]);







                /*
                |--------------------------------------------------------------------------
                | Create Expiry Event
                |--------------------------------------------------------------------------
                */


                SubscriptionEvent::firstOrCreate([

                    'subscription_id'=>$subscription->id,

                    'event'=>'expired',

                ],[

                    'old_status'=>$oldStatus,

                    'new_status'=>'expired',

                    'description'=>
                        'Subscription expired automatically.'

                ]);







                /*
                |--------------------------------------------------------------------------
                | Decide Target Devices
                |--------------------------------------------------------------------------
                */


                $devices = collect();




                /*
                |--------------------------------------------------------------------------
                | Device Based Subscription
                |--------------------------------------------------------------------------
                */


                if($subscription->device_id){


                    if($subscription->device){

                        $devices->push(

                            $subscription->device

                        );

                    }


                }




                /*
                |--------------------------------------------------------------------------
                | User Based Subscription
                |--------------------------------------------------------------------------
                */


                else{


                    $devices = $subscription
                        ->user
                        ->devices;


                }








                /*
                |--------------------------------------------------------------------------
                | Disable Protection
                |--------------------------------------------------------------------------
                */


                foreach($devices as $device){



                    $setting =

                        $device
                        ->protectionSetting;





                    if($setting){



                        $setting->update([



                            'protection_status'=>'expired',



                            'dns_protection'=>false,



                            'betting_block'=>false,



                            'adult_content_block'=>false,



                            'facebook_ad_block'=>false,



                            'youtube_ad_block'=>false,



                            'safe_search'=>false,



                        ]);



                    }



                }







            });



        }







        $this->info(

            'Expired subscriptions: '
            .
            $subscriptions->count()

        );





        return Command::SUCCESS;


    }


}