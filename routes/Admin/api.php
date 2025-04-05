<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonDetailController;

Route::group(['middleware'=>['auth:sanctum','admin'], 'prefix' => 'admin'],function()
{
    Route::get('/volunteer-requests', [VolunteerRequestController::class, 'request_index']);
    Route::put('/volunteer-requests/{id}', [VolunteerRequestController::class, 'update']);


    Route::get('/person-details', [PersonDetailController::class, 'index']); // Get all
    Route::get('/person-details/search', [PersonDetailController::class, 'search']); // Admin search
    Route::put('/person-details/{id}', [PersonDetailController::class, 'update']); // Admin can update
    Route::delete('/person-details/{id}', [PersonDetailController::class, 'destroy']); // Admin can delete
    Route::post('/person-details', [PersonDetailController::class, 'store']);

    Route::get('/family-details', [FamilyDetailController::class, 'index']);
    Route::get('/family-details/search', [FamilyDetailController::class, 'search']);
    Route::post('/family-details', [FamilyDetailController::class, 'store']);
    Route::put('/family-details/{id}', [FamilyDetailController::class, 'update']);
    Route::delete('/family-details/{id}', [FamilyDetailController::class, 'destroy']);

});