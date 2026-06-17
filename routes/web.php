<?php

use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\RecorridoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SecurityLogController;
use App\Http\Controllers\WebAuthController;
use App\Models\Opinion;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestOpinions = Opinion::with('project.category')->latest()->take(3)->get();
    if (auth()->check()) {
        auth()->user()->load('roles');
    }
    return view('home', compact('latestOpinions'));
})->name('home');

// Ayuda / preguntas frecuentes (pública)
Route::get('ayuda', [HelpController::class, 'index'])->name('ayuda');

// Auth web (sesion)
Route::middleware('guest')->group(function () {
    Route::get('login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [WebAuthController::class, 'login']);
    Route::get('register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [WebAuthController::class, 'register']);
});

Route::middleware('auth')
    ->post('logout', [WebAuthController::class, 'logout'])
    ->name('logout');

// Alarmas: centro de notificaciones del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('notificaciones', [NotificationController::class, 'index'])->name('notificaciones.index');
    Route::post('notificaciones/leer-todas', [NotificationController::class, 'readAll'])->name('notificaciones.read-all');
    Route::post('notificaciones/{id}/leer', [NotificationController::class, 'read'])->name('notificaciones.read');
});

// Reportes web: solo admin y moderador. Incluye exportación a PDF (dompdf).
Route::middleware(['auth', 'role:admin,moderador'])->group(function () {
    Route::get('reportes/resumen', [ReportController::class, 'resumen'])->name('reportes.resumen');
    Route::get('reportes/resumen/pdf', [ReportController::class, 'resumenPdf'])->name('reportes.resumen.pdf');
    Route::get('reportes/recorridos', [ReportController::class, 'recorridos'])->name('reportes.recorridos');
    Route::get('reportes/recorridos/pdf', [ReportController::class, 'recorridosPdf'])->name('reportes.recorridos.pdf');
});

// Logs de seguridad: solo admin
Route::middleware(['auth', 'role:admin'])
    ->get('seguridad/logs', [SecurityLogController::class, 'index'])
    ->name('seguridad.logs');

// CRUD web de opiniones
Route::resource('opiniones', OpinionController::class)
    ->names('opinions')
    ->parameters(['opiniones' => 'opinion']);

// CRUD web de recorridos (cabecera) con sus paradas (detalle)
// Lectura publica; crear/editar admin o moderador; eliminar solo admin.
Route::get('recorridos', [RecorridoController::class, 'index'])->name('recorridos.index');

Route::middleware(['auth', 'role:admin,moderador'])->group(function () {
    Route::get('recorridos/crear', [RecorridoController::class, 'create'])->name('recorridos.create');
    Route::post('recorridos', [RecorridoController::class, 'store'])->name('recorridos.store');
    Route::get('recorridos/{recorrido}/editar', [RecorridoController::class, 'edit'])->name('recorridos.edit');
    Route::put('recorridos/{recorrido}', [RecorridoController::class, 'update'])->name('recorridos.update');
});

Route::middleware(['auth', 'role:admin'])
    ->delete('recorridos/{recorrido}', [RecorridoController::class, 'destroy'])
    ->name('recorridos.destroy');

Route::get('recorridos/{recorrido}', [RecorridoController::class, 'show'])->name('recorridos.show');
