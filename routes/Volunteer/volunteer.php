<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonDetailController;
use App\Http\Controllers\FamilyDetailController;
use App\Http\Controllers\VolunteerRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccessTokenController;

Route::middleware(['auth:api', 'volunteer'])->group(function () {
    Route::get('/volunteer/person-details', [VolunteerRequestController::class, 'personDetails']);
});

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])->name('passport.token');

Route::group(['middleware'=>['auth:api', 'volunteer'], 'prefix' => 'volunteer'], function()
{
    Route::post('/logout', [AuthController::class, 'logout']);#tested

    Route::get('/person-details', [PersonDetailController::class, 'index']); // Only own records        #changes
    Route::put('/person-details/{person}', [PersonDetailController::class, 'update']); // Only own records  #tested
    Route::post('/person-details', [PersonDetailController::class, 'store']); // Can add new             #tested

    Route::get('/family-details', [FamilyDetailController::class, 'index']);
    Route::post('/family-details', [FamilyDetailController::class, 'store']); #tested
    Route::put('/family-details/{family}', [FamilyDetailController::class, 'update']); #tested
});