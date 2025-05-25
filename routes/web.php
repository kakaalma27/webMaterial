<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('auth.login'); 
})->name('login')->middleware('guest');
Route::post('/do-login', [UserController::class, 'authenticate'])->name('do-login');
Route::get('/register', [UserController::class, 'register'])->name('register'); // shows form
Route::post('/do-register', [UserController::class, 'store'])->name('do-register'); // processes form
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::prefix('admin/materials')->group(function () {
    Route::get('/', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/{id}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/{id}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
});