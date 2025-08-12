<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IineController;
use App\Http\Controllers\OkiniiriController;
use App\Http\Controllers\Api\ChatOverviewController;
use App\Http\Controllers\ChatController;

use Illuminate\Support\Facades\Validator;


use App\Http\Controllers\RewardRequestController;
use App\Http\Controllers\LocationController;


Route::middleware('auth:sanctum')->post('/me/location', [LocationController::class, 'update']);
Route::middleware('auth:sanctum')->get('/nearby', [LocationController::class, 'nearby']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/me/location', [LocationController::class, 'update']);
});

Route::middleware('auth:sanctum')->get('/chat/overview', [ChatOverviewController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->post('/rewards/request', [RewardRequestController::class, 'store']);


