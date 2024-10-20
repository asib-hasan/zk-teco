<?php
use App\Console\Commands\SendData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(SendData::class);
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(SendData::class)->everyMinute();
