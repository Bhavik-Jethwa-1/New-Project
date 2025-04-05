<?php
use App\Http\Controllers\PersonDetailController;
use App\Http\Controllers\AuthController;

Route::group(['middleware'=>['auth:sanctum','volunteer'], 'prefix' => 'volunteer'],function()
{
    Route::get('/person-details', [PersonDetailController::class, 'index']); // Only own records
    Route::put('/person-details/{id}', [PersonDetailController::class, 'update']); // Only own records
    Route::post('/person-details', [PersonDetailController::class, 'store']); // Can add new

    Route::get('/family-details', [FamilyDetailController::class, 'index']);
    Route::post('/family-details', [FamilyDetailController::class, 'store']);
    Route::put('/family-details/{id}', [FamilyDetailController::class, 'update']);
});