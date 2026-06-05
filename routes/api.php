<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OpinionController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

// Autenticacion: registro, login, logout
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login',    [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')
        ->post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Reportes: solo admin y moderador
Route::middleware(['auth:sanctum', 'role:admin,moderador'])
    ->get('reports/summary', [ReportController::class, 'summary'])
    ->name('api.reports.summary');

// Categories: lectura publica; escritura admin/moderador; borrado solo admin
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::middleware(['auth:sanctum', 'role:admin,moderador'])
    ->apiResource('categories', CategoryController::class)
    ->only(['store', 'update']);
Route::middleware(['auth:sanctum', 'role:admin'])
    ->apiResource('categories', CategoryController::class)
    ->only(['destroy']);

// Projects: lectura publica; crear auth; actualizar admin/moderador; borrar solo admin
Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')
    ->apiResource('projects', ProjectController::class)
    ->only(['store']);
Route::middleware(['auth:sanctum', 'role:admin,moderador'])
    ->apiResource('projects', ProjectController::class)
    ->only(['update']);
Route::middleware(['auth:sanctum', 'role:admin'])
    ->apiResource('projects', ProjectController::class)
    ->only(['destroy']);

// Todas las rutas de opiniones usan el prefijo de nombre "api."
// para no colisionar con los nombres del CRUD web (opinions.update, etc.)
Route::name('api.')->group(function () {

    // Lectura publica
    Route::apiResource('opinions', OpinionController::class)
        ->only(['index', 'show']);

    // Crear opinion: usuario autenticado
    Route::middleware('auth:sanctum')
        ->apiResource('opinions', OpinionController::class)
        ->only(['store']);

    // Actualizar: admin o moderador
    Route::middleware(['auth:sanctum', 'role:admin,moderador'])
        ->apiResource('opinions', OpinionController::class)
        ->only(['update']);

    // Eliminar: solo admin
    Route::middleware(['auth:sanctum', 'role:admin'])
        ->apiResource('opinions', OpinionController::class)
        ->only(['destroy']);
});
