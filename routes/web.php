<?php

use App\Models\Opinion;
use App\Http\Controllers\OpinionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $latestOpinions = Opinion::latest()->take(3)->get();

    return view('home', compact('latestOpinions'));
})->name('home');

Route::resource('opiniones', OpinionController::class)
    ->names('opinions')
    ->parameters(['opiniones' => 'opinion']);
