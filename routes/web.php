<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home / Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Order Routes
Route::get('/orders/create/{categoryId}', [OrderController::class, 'showOrderForm'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{orderId}/payment', [OrderController::class, 'showPayment'])->name('orders.payment');
Route::post('/orders/{orderId}/voucher', [OrderController::class, 'applyVoucher'])->name('orders.apply-voucher');
Route::post('/orders/{orderId}/payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.upload-payment');
Route::get('/orders/{orderId}/success', [OrderController::class, 'showSuccess'])->name('orders.success');

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/export-orders/{status?}', [ExportController::class, 'exportOrders'])->name('export.orders');
Route::post('/admin/orders/{orderId}/verify', [AdminController::class, 'verifyOrder'])->name('admin.orders.verify');
Route::post('/admin/orders/{orderId}/reject', [AdminController::class, 'rejectOrder'])->name('admin.orders.reject');
Route::post('/admin/categories/{categoryId}', [AdminController::class, 'updateTicketCategory'])->name('admin.categories.update');
Route::post('/admin/categories', [AdminController::class, 'createTicketCategory'])->name('admin.categories.create');
Route::post('/admin/vouchers', [AdminController::class, 'createVoucher'])->name('admin.vouchers.create');