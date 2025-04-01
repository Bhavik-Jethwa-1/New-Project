<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicDetailController;

Route::group(['middleware'=>['auth:sanctum','admin'], 'prefix' => 'admin'],function()
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/volunteer-requests', [VolunteerRequestController::class, 'request_index']);
    Route::put('/volunteer-requests/{id}', [VolunteerRequestController::class, 'update']);

    Route::put('/public-details/search', [PublicDetailController::class, 'search']);
    Route::get('/public-details/delete/{id}', [PublicDetailController::class, 'destroy']);
});