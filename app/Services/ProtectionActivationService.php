<?php

namespace App\Services;

use App\Models\DeviceProtectionSetting;
use App\Models\ProtectionSyncLog;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;


class ProtectionActivationService
{
    public static function activate(
        Subscription $subscription
    ): void
    {
        DB::transaction(function () use ($subscription) {

            /*
            |--------------------------------------------------------------------------
            | Load Required Relations
            |--------------------------------------------------------------------------
            */

            $subscription->loadMissing([
                'device',
                'plan',
            ]);


            $device = $subscription->device;
            $plan = $subscription->plan;


            /*
            |--------------------------------------------------------------------------
            | Device Must Exist
            |--------------------------------------------------------------------------
            */

            if (!$device) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Plan Must Exist
            |--------------------------------------------------------------------------
            */

            if (!$plan) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Read Plan Features
            |--------------------------------------------------------------------------
            */

            $features = is_array($plan->features)
                ? $plan->features
                : [];


            /*
            |--------------------------------------------------------------------------
            | Backward Compatibility
            |--------------------------------------------------------------------------
            |
            | Older data used:
            | adult_block
            |
            | Current standard:
            | adult_content_block
            |
            */

            if (
                array_key_exists(
                    'adult_block',
                    $features
                )
                &&
                !array_key_exists(
                    'adult_content_block',
                    $features
                )
            ) {
                $features[
                    'adult_content_block'
                ] = (bool) $features[
                    'adult_block'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize Features
            |--------------------------------------------------------------------------
            */

            $protectionFeatures = [

                'betting_block' =>
                    (bool) (
                        $features[
                            'betting_block'
                        ] ?? false
                    ),

                'adult_content_block' =>
                    (bool) (
                        $features[
                            'adult_content_block'
                        ] ?? false
                    ),

                'facebook_ad_block' =>
                    (bool) (
                        $features[
                            'facebook_ad_block'
                        ] ?? false
                    ),

                'youtube_ad_block' =>
                    (bool) (
                        $features[
                            'youtube_ad_block'
                        ] ?? false
                    ),

                'safe_search' =>
                    (bool) (
                        $features[
                            'safe_search'
                        ] ?? false
                    ),

                'dns_protection' =>
                    (bool) (
                        $features[
                            'dns_protection'
                        ] ?? false
                    ),
            ];


            /*
            |--------------------------------------------------------------------------
            | Create Device Protection Setting If Missing
            |--------------------------------------------------------------------------
            */

            $setting =
                DeviceProtectionSetting::firstOrCreate(
                    [
                        'device_id' =>
                            $device->id,
                    ],
                    [
                        'betting_block' =>
                            false,

                        'adult_content_block' =>
                            false,

                        'facebook_ad_block' =>
                            false,

                        'youtube_ad_block' =>
                            false,

                        'safe_search' =>
                            false,

                        'dns_protection' =>
                            false,

                        'protection_status' =>
                            'inactive',
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | Apply Plan Features
            |--------------------------------------------------------------------------
            */

            $setting->update([

                'betting_block' =>
                    $protectionFeatures[
                        'betting_block'
                    ],

                'adult_content_block' =>
                    $protectionFeatures[
                        'adult_content_block'
                    ],

                'facebook_ad_block' =>
                    $protectionFeatures[
                        'facebook_ad_block'
                    ],

                'youtube_ad_block' =>
                    $protectionFeatures[
                        'youtube_ad_block'
                    ],

                'safe_search' =>
                    $protectionFeatures[
                        'safe_search'
                    ],

                'dns_protection' =>
                    $protectionFeatures[
                        'dns_protection'
                    ],

                'protection_status' =>
                    'active',

                'disabled_reason' =>
                    null,

                'last_sync_at' =>
                    now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Build Fresh Protection Payload
            |--------------------------------------------------------------------------
            */

            $payload =
                ProtectionEngineService::payload(
                    $device
                );


            $rules = collect(
                $payload['rules']
            )
            ->sortBy('id')
            ->values()
            ->toArray();


            $syncData = [

                'protection' =>
                    $payload['protection'],

                'rules' =>
                    $rules,
            ];


            /*
            |--------------------------------------------------------------------------
            | Generate Rules Hash
            |--------------------------------------------------------------------------
            */

            $hash = hash(
                'sha256',
                json_encode(
                    $syncData
                )
            );


            /*
            |--------------------------------------------------------------------------
            | Get Next Sync Version
            |--------------------------------------------------------------------------
            */

            $lastVersion =
                ProtectionSyncLog::where(
                    'device_id',
                    $device->id
                )
                ->lockForUpdate()
                ->max(
                    'sync_version'
                );


            /*
            |--------------------------------------------------------------------------
            | Create Pending Sync
            |--------------------------------------------------------------------------
            */

            ProtectionSyncLog::create([

                'device_id' =>
                    $device->id,

                'sync_version' =>
                    ($lastVersion ?? 0) + 1,

                'rules_hash' =>
                    $hash,

                'apply_status' =>
                    'pending',

                'retry_count' =>
                    0,

                'max_retry' =>
                    3,

                'device_version' =>
                    $device->app_version,

                'synced_at' =>
                    null,
            ]);

        });
    }
}