<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\UserController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
    
    // Listar usuarios
    Route::get('/', [UserController::class, 'index']);

    // Crear usuario
    Route::post('/', [UserController::class, 'store']);

    // Mostrar un usuario
    Route::get('/{id}', [UserController::class, 'show']);

    // Actualizar usuario
    Route::put('/{id}', [UserController::class, 'update']);

    // Cambiar contraseña
    Route::put('/{id}/password', [UserController::class, 'changePassword']);

    // Eliminar usuario
    Route::delete('/{id}', [UserController::class, 'destroy']);
});



// Ruta de inicio de sesión
Route::post('/login', [AuthController::class, 'login']);

// Ruta de cierre de sesión
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::get('/barrios', [BarrioController::class, 'index']);
Route::post('/barrios/por-ruta', [BarrioController::class, 'barriosPorRuta']);

