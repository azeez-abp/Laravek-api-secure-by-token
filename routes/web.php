<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//    // return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\V1\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/token-generate', [App\Http\Controllers\V1\DashboardController::class, 'showToken'])->name('token-generate');
    Route::post('/create-token', [App\Http\Controllers\V1\DashboardController::class, 'createToken'])->name('createToken_');
    Route::delete('/delete-token/{id}', [App\Http\Controllers\V1\DashboardController::class, 'deleteToken'])->name('deleteToken');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
