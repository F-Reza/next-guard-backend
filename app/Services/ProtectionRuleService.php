<?php

namespace App\Services;


use App\Models\ProtectionRule;


class ProtectionRuleService
{


    public static function create(
        array $data
    ): ProtectionRule
    {

        return ProtectionRule::create([

            'device_id'=>$data['device_id'] ?? null,

            'category'=>$data['category'],

            'domain'=>$data['domain'],

            'rule_type'=>$data['rule_type'],

            'status'=>$data['status'] ?? 'active',

            'description'=>$data['description'] ?? null,

        ]);

    }



    public static function list(
        ?int $deviceId = null
    )
    {

        $query = ProtectionRule::latest();


        if($deviceId){

            $query->where(
                'device_id',
                $deviceId
            );

        }


        return $query->get();

    }



    public static function delete(
        ProtectionRule $rule
    ): bool
    {

        return $rule->delete();

    }


}