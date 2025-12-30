<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily sales report at 18:00
Schedule::command('sales:daily-report')->dailyAt('18:00');

// Check low stock every 30 minutes
Schedule::command('stock:check-low')->everyThirtyMinutes();
