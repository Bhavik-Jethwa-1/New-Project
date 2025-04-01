<?php
use App\Http\Controllers\PublicDetailController;
use App\Http\Controllers\AuthController;

Route::group(['middleware'=>['auth:sanctum','volunteer'], 'prefix' => 'volunteer'],function()
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/public-details', [PublicDetailController::class, 'store']);
    Route::put('/public-details/{id}', [PublicDetailController::class, 'update']);
});