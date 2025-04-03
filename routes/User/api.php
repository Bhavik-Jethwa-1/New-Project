<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRequestController;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware'=>['auth:sanctum','user'], 'prefix' => 'user'],function()
{
    Route::post('/volunteer-requests', [VolunteerRequestController::class, 'store']);
});