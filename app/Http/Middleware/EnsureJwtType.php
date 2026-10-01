<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EnsureJwtType
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$allowedTypes
    ): Response {

        try {
            $type = JWTAuth::payload()->get('type');

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid authentication token.',
            ], 401);
        }


        if (!in_array($type, $allowedTypes, true)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid token type for this endpoint.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | Enrollment token = first device only
        |--------------------------------------------------------------------------
        */

        if ($type === 'enrollment') {

            $user = auth('api')->user();

            if (!$user) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }


            if ($user->devices()->exists()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Device enrollment token is no longer valid.',
                ], 409);
            }
        }


        return $next($request);
    }
}