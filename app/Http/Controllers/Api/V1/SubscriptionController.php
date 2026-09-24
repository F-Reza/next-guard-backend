<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{

    /**
     * Activate subscription.
     */
    public function activate(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'plan_id' => [
                'required',
                'exists:subscription_plans,id'
            ],

            'device_id' => [
                'nullable',
                'exists:devices,id'
            ],

            'source' => [
                'nullable',
                'in:payment,license,admin'
            ],

            'payment_reference' => [
                'nullable',
                'string',
                'max:255'
            ]

        ]);


        if ($validator->fails()) {

            return response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $validator->errors()

            ], 422);

        }



        $user = auth('api')->user();



        $plan = SubscriptionPlan::find(
            $request->plan_id
        );



        $device = null;



        if ($request->device_id) {


            $device = $user->devices()
                ->where(
                    'id',
                    $request->device_id
                )
                ->first();



            if (!$device) {

                return response()->json([

                    'success' => false,

                    'message' => 'Device not found.'

                ], 404);

            }

        }



        $startsAt = now();


        $expiresAt = now()->addDays(
            $plan->duration_days
        );



        $subscription = Subscription::create([

            'user_id' => $user->id,

            'subscription_plan_id' => $plan->id,

            'device_id' => $device?->id,

            'starts_at' => $startsAt,

            'expires_at' => $expiresAt,

            'status' => 'active',

            'source' => $request->source ?? 'payment',

            'payment_reference' =>
                $request->payment_reference,

        ]);



        return response()->json([

            'success' => true,

            'message' => 'Subscription activated successfully.',

            'data' => [

                'subscription' => $subscription
                    ->load('plan')

            ]

        ], 201);

    }




    /**
     * Get all user subscriptions.
     */
    public function index(): JsonResponse
    {

        $user = auth('api')->user();



        $subscriptions = $user->subscriptions()
            ->with('plan')
            ->latest()
            ->get();



        foreach ($subscriptions as $subscription) {


            if (

                $subscription->status === 'active'

                &&

                $subscription->expires_at

                &&

                $subscription->expires_at->isPast()

            ) {


                $subscription->update([

                    'status' => 'expired'

                ]);

            }

        }



        return response()->json([

            'success' => true,

            'message' => 'Subscriptions retrieved.',

            'data' => [

                'subscriptions' => $subscriptions

            ]

        ]);

    }




    /**
     * Get current active subscription.
     */
    public function current(): JsonResponse
    {

        $user = auth('api')->user();



        $subscription = $user->subscriptions()

            ->where(
                'status',
                'active'
            )

            ->with('plan')

            ->latest()

            ->first();



        if (!$subscription) {


            return response()->json([

                'success' => false,

                'message' =>
                    'No active subscription found.'

            ], 404);

        }



        if (

            $subscription->expires_at

            &&

            $subscription->expires_at->isPast()

        ) {


            $subscription->update([

                'status' => 'expired'

            ]);



            return response()->json([

                'success' => false,

                'message' =>
                    'Subscription expired.'

            ], 403);

        }



        return response()->json([

            'success' => true,

            'message' =>
                'Active subscription retrieved.',

            'data' => [

                'subscription' => $subscription

            ]

        ]);

    }


}