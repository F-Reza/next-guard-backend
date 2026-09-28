<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Services\DeviceOfflineDetector;


class MarkOfflineDevices extends Command
{


    protected $signature = 'devices:mark-offline';



    protected $description =
        'Mark devices offline when heartbeat timeout';



    public function handle(): int
    {


        $count =
            DeviceOfflineDetector::detect();



        $this->info(
            "Offline devices: ".$count
        );



        return Command::SUCCESS;


    }


}