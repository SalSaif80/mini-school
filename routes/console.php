<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// // Scheduled tasks are defined in app/Console/Kernel.php
// Schedule::command('logs:send-to-vault')->everyMinute(); // معطل حتى إصلاح التوكن

// // Clean old activity logs monthly
// Schedule::command('activitylog:clean')->monthly();
