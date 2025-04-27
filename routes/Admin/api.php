<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonDetailController;

Route::group(['middleware'=>['auth:sanctum','admin'], 'prefix' => 'admin'],function()
{

    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/volunteer-requests', [VolunteerRequestController::class, 'request_index']); #tested
    Route::put('/volunteer-requests/{volunteerRequest}', [VolunteerRequestController::class, 'update']); #tested


    Route::get('/person-details', [PersonDetailController::class, 'index']);
    Route::put('/person-details/{person}', [PersonDetailController::class, 'update']); // Admin can update
    Route::delete('/person-details/{person}', [PersonDetailController::class, 'destroy']); // Admin can delete 
    Route::post('/person-details', [PersonDetailController::class, 'store']); #tested

    Route::get('/family-details', [FamilyDetailController::class, 'index']);
    Route::post('/family-details', [FamilyDetailController::class, 'store']); #tested
    Route::put('/family-details/{family}', [FamilyDetailController::class, 'update']);
    Route::delete('/family-details/{family}', [FamilyDetailController::class, 'destroy']);

});