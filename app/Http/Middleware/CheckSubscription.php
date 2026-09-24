<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{

    public function handle(
        Request $request,
        Closure $next
    ): Response {


        $user = auth('api')->user();


        if (!$user) {

            return response()->json([

                'success' => false,

                'message' => 'Unauthenticated.'

            ],401);

        }



        $subscription = $user->subscriptions()

            ->where(
                'status',
                'active'
            )

            ->latest()

            ->first();



        if (!$subscription) {

            return response()->json([

                'success' => false,

                'message' => 'Active subscription required.'

            ],403);

        }



        if (

            $subscription->expires_at

            &&

            $subscription->expires_at->isPast()

        ) {


            $subscription->update([

                'status'=>'expired'

            ]);



            return response()->json([

                'success'=>false,

                'message'=>'Subscription expired.'

            ],403);

        }



        return $next($request);

    }

}