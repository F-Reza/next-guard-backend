<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Device;
use App\Models\ProtectionRule;
use App\Models\DeviceProtectionSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProtectionTest extends TestCase
{

    use RefreshDatabase;



    private function createDevice()
    {

        $user = User::factory()->create();


        $token = auth('api')
            ->login($user);



        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                uniqid()
            ),

            'platform'=>'android',

            'status'=>'active',

        ]);




        $plan = \App\Models\SubscriptionPlan::create([

            'name'=>'Test Monthly',

            'price'=>10,

            'duration_days'=>30,

            'status'=>'active',

        ]);




        \App\Models\Subscription::create([

            'user_id'=>$user->id,

            'device_id'=>$device->id,

            'subscription_plan_id'=>$plan->id,

            'status'=>'active',

            'started_at'=>now(),

            'expires_at'=>now()->addMonth(),

        ]);




        return [
            $user,
            $device,
            $token
        ];

    }




    /**
     * Protection status
     */
    public function test_device_can_view_protection_status()
    {

        [$user,$device,$token] = $this->createDevice();



        DeviceProtectionSetting::create([

            'device_id'=>$device->id,

            'protection_status'=>'active',

            'dns_protection'=>true,

            'safe_search'=>true,

            'betting_block'=>false,

        ]);




        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->getJson(
                '/api/v1/devices/'.$device->id.'/protection/status'
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Update protection settings
     */
    public function test_device_can_update_protection()
    {

        [$user,$device,$token] = $this->createDevice();



        DeviceProtectionSetting::create([

            'device_id'=>$device->id,

            'protection_status'=>'active',

            'dns_protection'=>true,

            'safe_search'=>false,

            'betting_block'=>false,

        ]);




        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->postJson(
                '/api/v1/devices/'.$device->id.'/protection/update',
                [

                    'betting_block'=>true,

                    'safe_search'=>true

                ]
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Protection rule list
     */
    public function test_device_can_view_rules()
    {

        [$user,$device,$token] = $this->createDevice();



        ProtectionRule::create([

            'category'=>'betting',

            'domain'=>'bet365.com',

            'rule_type'=>'domain',

            'description'=>'test rule'

        ]);




        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->getJson(
                '/api/v1/devices/'.$device->id.'/protection-rules'
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Blocked domain check
     */
    public function test_blocked_domain_is_detected()
    {

        [$user,$device,$token] = $this->createDevice();



        DeviceProtectionSetting::create([

            'device_id'=>$device->id,

            'protection_status'=>'active',

            'betting_block'=>true,

            'dns_protection'=>true,

        ]);



        ProtectionRule::create([

            'category'=>'betting',

            'domain'=>'bet365.com',

            'rule_type'=>'domain',

            'description'=>'Betting block'

        ]);




        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->postJson(
                '/api/v1/devices/'.$device->id.'/protection/check',
                [

                    'domain'=>'bet365.com'

                ]
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true,
                'data'=>[
                    'allowed'=>false
                ]
            ]);

    }





    /**
     * Allowed domain check
     */
    public function test_allowed_domain_passes()
    {

        [$user,$device,$token] = $this->createDevice();



        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->postJson(
                '/api/v1/devices/'.$device->id.'/protection/check',
                [

                    'domain'=>'google.com'

                ]
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true,
                'data'=>[
                    'allowed'=>true
                ]
            ]);

    }


}