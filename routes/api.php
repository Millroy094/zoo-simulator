<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZooController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix'=>'zoo','as'=>'zoo.'], function(){
    Route::post('/create', [ZooController::class, 'createZoo']);
    Route::delete('/destroy', [ZooController::class, 'destroyZoo']);
    Route::put('/increment-hour', [ZooController::class, 'incrementHourAtZoo']);
    Route::put('/feed', [ZooController::class, 'feedZoo']);
});


