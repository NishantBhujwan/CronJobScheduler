<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronJobController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test',function(){
    return "hello test api1111";
});
Route::get('/cron-jobs', [CronJobController::class, 'index']);

Route::post('/cron-jobs', [CronJobController::class, 'store']);

Route::delete('/cron-jobs/{id}', [CronJobController::class, 'destroy']);
