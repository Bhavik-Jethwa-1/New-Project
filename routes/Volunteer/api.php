<?php
use App\Http\Controllers\PersonDetailController;
use App\Http\Controllers\FamilyDetailController;
use App\Http\Controllers\AuthController;

Route::group(['middleware'=>['auth:sanctum','volunteer'], 'prefix' => 'volunteer'],function()
{
    Route::post('/logout', [AuthController::class, 'logout']);#tested

    Route::get('/person-details', [PersonDetailController::class, 'index']); // Only own records        #tested
    Route::put('/person-details/{id}', [PersonDetailController::class, 'update']); // Only own records  #tested
    Route::post('/person-details', [PersonDetailController::class, 'store']); // Can add new             #tested

    Route::get('/family-details', [FamilyDetailController::class, 'index']); #tested
    Route::post('/family-details', [FamilyDetailController::class, 'store']); #tested
    Route::put('/family-details/{id}', [FamilyDetailController::class, 'update']); #tested
});