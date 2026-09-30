<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class AuthTest extends TestCase
{

    use RefreshDatabase;



    /**
     * Register success
     */
    public function test_user_can_register()
    {

        $response = $this->postJson(
            '/api/v1/auth/register',
            [
                'name'=>'Test User',
                'email'=>'test@example.com',
                'password'=>'password',
                'password_confirmation'=>'password',
            ]
        );


        $response
            ->assertStatus(201)
            ->assertJson([
                'success'=>true
            ]);


        $this->assertDatabaseHas(
            'users',
            [
                'email'=>'test@example.com'
            ]
        );

    }





    /**
     * Duplicate email reject
     */
    public function test_duplicate_email_cannot_register()
    {

        User::factory()->create([
            'email'=>'test@example.com'
        ]);


        $response = $this->postJson(
            '/api/v1/auth/register',
            [
                'name'=>'Test User',
                'email'=>'test@example.com',
                'password'=>'password',
                'password_confirmation'=>'password',
            ]
        );


        $response->assertStatus(422);

    }





    /**
     * Login success
     */
    public function test_user_can_login()
    {

        $user = User::factory()->create([
            'email'=>'test@example.com',
            'password'=>bcrypt('password')
        ]);


        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                'test-device-uuid'
            ),

            'platform'=>'android',

            'status'=>'active',

        ]);



        $response = $this->postJson(
            '/api/v1/auth/login',
            [

                'login'=>'test@example.com',

                'password'=>'password',

                'device_id'=>$device->id

            ]
        );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Wrong password
     */
    public function test_wrong_password_rejected()
    {

        $user = User::factory()->create([
            'email'=>'test@example.com',
            'password'=>bcrypt('password')
        ]);



        $device = Device::create([

            'user_id'=>$user->id,

            'device_uuid_hash'=>hash(
                'sha256',
                'test-device-uuid'
            ),

            'platform'=>'android',

            'status'=>'active',

        ]);



        $response = $this->postJson(
            '/api/v1/auth/login',
            [

                'login'=>'test@example.com',

                'password'=>'wrong-password',

                'device_id'=>$device->id

            ]
        );



        $response->assertStatus(401);

    }





    /**
     * Auth user me endpoint
     */
    public function test_authenticated_user_can_view_profile()
    {

        $user = User::factory()->create();


        $token = auth('api')
            ->login($user);



        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->getJson(
                '/api/v1/auth/me'
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }





    /**
     * Logout
     */
    public function test_user_can_logout()
    {

        $user = User::factory()->create();


        $token = auth('api')
            ->login($user);



        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token
            )
            ->postJson(
                '/api/v1/auth/logout'
            );



        $response
            ->assertStatus(200)
            ->assertJson([
                'success'=>true
            ]);

    }


}