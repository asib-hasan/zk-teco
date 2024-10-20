<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('device',[DeviceController::class,'index']);

Route::get('/run-schedule', function () {
    Artisan::call('schedule:work');
    return 'Schedule work command executed!';
});
