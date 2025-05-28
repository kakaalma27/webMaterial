<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaintController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PlumbingController;
use App\Http\Controllers\ElectricalController;

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
Route::prefix('admin/electricals')->group(function () {
    Route::get('/', [ElectricalController::class, 'index'])->name('electricals.index');
    Route::get('/create', [ElectricalController::class, 'create'])->name('electricals.create');
    Route::post('/', [ElectricalController::class, 'store'])->name('electricals.store');
    Route::get('/{id}', [ElectricalController::class, 'show'])->name('electricals.show');
    Route::get('/{id}/edit', [ElectricalController::class, 'edit'])->name('electricals.edit');
    Route::put('/{id}', [ElectricalController::class, 'update'])->name('electricals.update');
    Route::delete('/{id}', [ElectricalController::class, 'destroy'])->name('electricals.destroy');
});
Route::prefix('admin/plumbings')->group(function () {
    Route::get('/', [PlumbingController::class, 'index'])->name('plumbings.index');
    Route::get('/create', [PlumbingController::class, 'create'])->name('plumbings.create');
    Route::post('/', [PlumbingController::class, 'store'])->name('plumbings.store');
    Route::get('/{id}', [PlumbingController::class, 'show'])->name('plumbings.show');
    Route::get('/{id}/edit', [PlumbingController::class, 'edit'])->name('plumbings.edit');
    Route::put('/{id}', [PlumbingController::class, 'update'])->name('plumbings.update');
    Route::delete('/{id}', [PlumbingController::class, 'destroy'])->name('plumbings.destroy');
});
Route::prefix('admin/paints')->group(function () {
    Route::get('/', [PaintController::class, 'index'])->name('paints.index');
    Route::get('/create', [PaintController::class, 'create'])->name('paints.create');
    Route::post('/', [PaintController::class, 'store'])->name('paints.store');
    Route::get('/{id}', [PaintController::class, 'show'])->name('paints.show');
    Route::get('/{id}/edit', [PaintController::class, 'edit'])->name('paints.edit');
    Route::put('/{id}', [PaintController::class, 'update'])->name('paints.update');
    Route::delete('/{id}', [PaintController::class, 'destroy'])->name('paints.destroy');
});