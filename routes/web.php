<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaintController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PlumbingController;
use App\Http\Controllers\ElectricalController;
use App\Http\Controllers\PaymentHistoryController;

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
Route::get('/register', [UserController::class, 'register'])->name('register'); 
Route::post('/do-register', [UserController::class, 'store'])->name('do-register'); 
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/admin/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('/admin/user', [UserController::class, 'user'])->name('user');
Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('/admin/history', [PaymentHistoryController::class, 'salesHistory'])->name('admin.history');
Route::get('/admin/export/pdf', [PaymentHistoryController::class, 'exportExcel'])->name('admin.excel');
Route::get('/admin/export/excel', [PaymentHistoryController::class, 'exportPdf'])->name('admin.pdf');

Route::group(['middleware' => ['auth']], function () { // Assuming authenticated users for these
    Route::get('/karyawan', [SaleController::class, 'index'])->name('karyawan.index');
    Route::post('/cart/add', [SaleController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [SaleController::class, 'showCart'])->name('cart.show');
    Route::delete('/cart/remove', [SaleController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update-quantity', [SaleController::class, 'updateCartQuantity'])->name('cart.updateQuantity');
    Route::post('/checkout', [SaleController::class, 'store'])->name('karyawan.store'); // Renamed from original store to avoid confusion with single item.
    Route::get('/karyawan/{type}/{id}', [SaleController::class, 'show'])->name('karyawan.show'); // This method will now ONLY show product details and its sales history.
    Route::get('/sales/filter/{type}/{id}', [SaleController::class, 'filter'])->name('sales.filter');
    Route::get('/karyawan/receipt', [SaleController::class, 'showReceipt'])->name('karyawan.receipt');
});
Route::prefix('admin/materials')->group(function () {
    Route::get('/', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/{id}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/{id}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
});
Route::prefix('admin/restock')->group(function () {
    Route::get('/', [RestockController::class, 'index'])->name('restock.index');
    Route::post('/', [RestockController::class, 'store'])->name('restock.store');
    Route::put('/{id}', [RestockController::class, 'update'])->name('restock.update');
    Route::delete('/{id}', [RestockController::class, 'destroy'])->name('restock.destroy');
    Route::get('/product-details/{type}/{id}', [RestockController::class, 'getProductDetails'])->name('restock.product_details');
    Route::get('/create', [RestockController::class, 'create'])->name('restock.create');
    Route::get('/{id}', [RestockController::class, 'show'])->name('restock.show');
    Route::get('/{id}/edit', [RestockController::class, 'edit'])->name('restock.edit');
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
Route::prefix('admin/payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payment.index'); // index
    Route::post('/', [PaymentController::class, 'store'])->name('payment.store'); // simpan metode baru
    Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('payment.edit');
    Route::put('/{payment}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');
});
