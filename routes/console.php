<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// backup commands
Schedule::command('backup:run')
    ->daily()
    ->at('23:00')
    ->withoutOverlapping();

Schedule::command('backup:clean')
    ->daily()
    ->at('23:30')
    ->withoutOverlapping();

Schedule::command('backup:monitor')
    ->daily()
    ->at('23:45')
    ->withoutOverlapping();

Schedule::command('activitylog:clean')
    ->weekly()
    ->sundays()
    ->at('03:00');


    
// notification command
Schedule::command('notifications:generate')
    ->hourly();    