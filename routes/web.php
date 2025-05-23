<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobController::class, 'index']);
Route::get('/search', SearchController::class);
Route::get('/tags/{tag:name}', TagController::class);
Route::middleware('guest')->group(function(){
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);   
});
Route::middleware('auth')->group(function(){
    Route::delete('/logout', [LoginController::class, 'destroy']);
    Route::get('/jobs/create', [JobController::class, 'create']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs/{job}/edit', [JobController::class, 'edit']);
    Route::patch('jobs/{job}', [JobController::class, 'update']);
    Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
    Route::get('/jobs/{job}', [JobController::class, 'show']);
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});