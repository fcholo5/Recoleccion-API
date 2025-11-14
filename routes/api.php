<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ruta de inicio de sesión
Route::post('/login', [AuthController::class, 'login']);

// Ruta de cierre de sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

