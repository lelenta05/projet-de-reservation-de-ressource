<?php

use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RessourceController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#Route::get('/user', function (Request $request) {
 #   return $request->user();
#})->middleware('auth:sanctum');
//les routes pour l'auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
//les routes proteger par le middleware sanctum
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);
});
Route::apiResource('ressources',RessourceController::class);
Route::apiResource('reservations',ReservationController::class);

