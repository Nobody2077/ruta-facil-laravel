<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OpinionController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RecorridoController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SecurityLogController;
use Illuminate\Support\Facades\Route;

// Autenticacion: registro, login, logout
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login',    [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')
        ->post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Reportes: solo admin y moderador
Route::middleware(['auth:sanctum', 'role:admin,moderador'])->group(function () {
    Route::get('reports/summary', [ReportController::class, 'summary'])->name('api.reports.summary');
    Route::get('reports/recorridos', [ReportController::class, 'recorridos'])->name('api.reports.recorridos');
});

// Alarmas: notificaciones del usuario autenticado
Route::middleware('auth:sanctum')->group(function () {
    Route::get('notifications', [NotificationController::class, 'index'])->name('api.notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('api.notifications.read');
});

// Logs de seguridad: solo admin
Route::middleware(['auth:sanctum', 'role:admin'])
    ->get('security-logs', [SecurityLogController::class, 'index'])
    ->name('api.security-logs.index');

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

// Recorridos (cabecera-detalle): lectura publica; crear auth; actualizar admin/moderador; borrar solo admin.
// Se usa el prefijo de nombre "api." para no colisionar con los nombres del CRUD web (recorridos.update, etc.)
Route::name('api.')->group(function () {
    Route::apiResource('recorridos', RecorridoController::class)->only(['index', 'show']);
    Route::middleware('auth:sanctum')
        ->apiResource('recorridos', RecorridoController::class)
        ->only(['store']);
    Route::middleware(['auth:sanctum', 'role:admin,moderador'])
        ->apiResource('recorridos', RecorridoController::class)
        ->only(['update']);
    Route::middleware(['auth:sanctum', 'role:admin'])
        ->apiResource('recorridos', RecorridoController::class)
        ->only(['destroy']);
});

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
