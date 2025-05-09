<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Guest\TaskController as GuestTaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::prefix('guest')->group(function () {
    Route::put('tasks/update-status/{task}', [GuestTaskController::class, 'updateStatus']);
    Route::apiResource('tasks', GuestTaskController::class);
});


Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('tasks/update-status/{task}', [TaskController::class, 'updateStatus']);
    Route::apiResource('tasks', TaskController::class);
});

