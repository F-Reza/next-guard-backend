<?php

namespace App\Jobs;


use App\Models\Subscription;
use App\Services\SubscriptionService;
use App\Services\ProtectionDeactivationService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;



class ExpireSubscriptionsJob implements ShouldQueue
{

    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;



    public function handle(): void
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



            // expire subscription

            SubscriptionService::expire(
                $subscription
            );



            // disable protection

            ProtectionDeactivationService::deactivate(
                $subscription,
                'subscription_expired'
            );



        }


    }

}