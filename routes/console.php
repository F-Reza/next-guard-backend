<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\RetryFailedProtectionSync;
// use App\Jobs\ExpireSubscriptionsJob;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(
    new RetryFailedProtectionSync()
)
->everyFiveMinutes();


Schedule::command(
    'subscriptions:expire'
)// ->daily();
->everyMinute();


Schedule::command(
    'protection:check-timeout'
)
->everyMinute();


Schedule::command(
    'devices:mark-offline'
)
->everyFiveMinutes();


// Schedule::command(
//     'protection:retry-sync'
// )
// ->everyMinute();