<?php

use App\Http\Controllers\OpinionController;
use App\Http\Controllers\WebAuthController;
use App\Models\Opinion;
use App\Services\ReportService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestOpinions = Opinion::with('project.category')->latest()->take(3)->get();
    if (auth()->check()) {
        auth()->user()->load('roles');
    }
    return view('home', compact('latestOpinions'));
})->name('home');

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

// Reporte web: solo admin y moderador
Route::middleware(['auth', 'role:admin,moderador'])
    ->get('reportes/resumen', function () {
        $data = app(ReportService::class)->summary();
        return view('reportes.resumen', compact('data'));
    })->name('reportes.resumen');

// CRUD web de opiniones
Route::resource('opiniones', OpinionController::class)
    ->names('opinions')
    ->parameters(['opiniones' => 'opinion']);
