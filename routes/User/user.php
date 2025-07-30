<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccessTokenController;



//Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])->name('passport.token');

Route::post('/register', [AuthController::class, 'register']);#tested
Route::post('/login', [AuthController::class, 'login']);#tested

Route::group(['middleware'=>['auth:api', 'user'], 'prefix' => 'user'], function()
{
    Route::post('/logout', [AuthController::class, 'logout']);
});