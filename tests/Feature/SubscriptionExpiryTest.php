<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Device;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\DeviceProtectionSetting;
use App\Models\SubscriptionEvent;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;



class SubscriptionExpiryTest extends TestCase
{

    use RefreshDatabase;



    private function createExpiredSubscription()
    {


        $user = User::factory()->create();



        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                uniqid()
            ),

            'platform'=>'android',

            'status'=>'active',

        ]);





        $plan = SubscriptionPlan::create([

            'name'=>'Monthly',

            'price'=>10,

            'duration_days'=>30,

            'status'=>'active',

        ]);






        $subscription = Subscription::create([

            'user_id'=>$user->id,

            'device_id'=>$device->id,

            'subscription_plan_id'=>$plan->id,

            'status'=>'active',

            'started_at'=>now()
                ->subMonth(),

            'expires_at'=>now()
                ->subDay(),

        ]);





        DeviceProtectionSetting::create([

            'device_id'=>$device->id,

            'protection_status'=>'active',

            'dns_protection'=>true,

            'betting_block'=>true,

            'safe_search'=>true,

        ]);





        return [

            $subscription,

            $device

        ];

    }






    /**
     * Expired subscription should deactivate protection
     */
    public function test_expired_subscription_deactivates_protection()
    {


        [$subscription,$device] =
            $this->createExpiredSubscription();




        $this->artisan(
            'subscriptions:expire'
        )
        ->assertExitCode(0);






        $this->assertDatabaseHas(
            'subscriptions',
            [

                'id'=>$subscription->id,

                'status'=>'expired'

            ]
        );






        $this->assertDatabaseHas(
            'device_protection_settings',
            [

                'device_id'=>$device->id,

                'protection_status'=>'inactive'

            ]
        );



    }







    /**
     * Expiry event created
     */
    public function test_subscription_expiry_event_created()
    {


        [$subscription,$device] =
            $this->createExpiredSubscription();




        $this->artisan(
            'subscriptions:expire'
        );





        $this->assertDatabaseHas(
            'subscription_events',
            [

                'subscription_id'=>$subscription->id,

                'event'=>'expired',

                'new_status'=>'expired'

            ]
        );


    }



}