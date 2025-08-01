<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


require __DIR__ .'/Admin/admin.php';
require __DIR__ .'/User/user.php';
require __DIR__ .'/Volunteer/volunteer.php';
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|


Route::middleware('auth:sanctum')->get('/user', faunction (Request $request) {
    return $request->user();
});
*/

// routes/api.php
Route::middleware('auth:api')->get('/volunteer/person-details', [VolunteerRequestController::class, 'personDetails']);
