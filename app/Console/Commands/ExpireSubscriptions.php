<?php

namespace App\Console\Commands;


use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Services\ProtectionDeactivationService;

use Illuminate\Console\Command;



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



            $oldStatus = $subscription->status;





            /*
            |--------------------------------------------------------------------------
            | Update Subscription Status
            |--------------------------------------------------------------------------
            */


            $subscription->update([

                'status'=>'expired'

            ]);







            /*
            |--------------------------------------------------------------------------
            | Disable Protection
            |--------------------------------------------------------------------------
            */


            ProtectionDeactivationService::deactivate(
                $subscription,
                'subscription_expired'
            );








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





        }







        $this->info(

            'Expired subscriptions: '
            .
            $subscriptions->count()

        );





        return Command::SUCCESS;


    }


}