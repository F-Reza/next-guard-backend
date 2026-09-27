<?php

namespace App\Services;


use App\Models\User;


class DeviceLimitService
{


    /**
     * Get active device limit
     */
    public static function limit(
        User $user
    ): int
    {

        $subscription = $user
            ->subscriptions()
            ->where(
                'status',
                'active'
            )
            ->with('plan')
            ->latest()
            ->first();



        return $subscription?->plan?->device_limit ?? 1;

    }






    /**
     * Current active devices count
     */
    public static function current(
        User $user
    ): int
    {


        return $user
            ->devices()
            ->where(
                'status',
                'active'
            )
            ->count();


    }







    /**
     * Can add device?
     */
    public static function canAdd(
        User $user
    ): bool
    {


        return self::current($user)
            <
            self::limit($user);


    }







    /**
     * Device limit information
     */
    public static function info(
        User $user
    ): array
    {


        return [

            'limit'=>self::limit($user),

            'current'=>self::current($user),

            'available'=>
                self::limit($user)
                -
                self::current($user),

        ];


    }



}