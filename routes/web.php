<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\HomeController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\MerchantOrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\StandManagerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;





// Rute Root (Halaman Utama murni) -> Otomatis lempar ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ----------------------------------------------------
// GERBANG AKSES PUBLIC (Belum Login)
// ----------------------------------------------------
// Fitur Login Mahasiswa & Stand
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Fitur Registrasi Mahasiswa Baru
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// --- LOGIN KHUSUS PEDAGANG / MERCHANT ---
Route::get('/merchant/login', [AuthController::class, 'showMerchantLoginForm'])->name('merchant.login');
Route::post('/merchant/login', [AuthController::class, 'merchantLogin'])->name('merchant.login.submit');

// --- LOGIN KHUSUS ADMIN ---
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// ----------------------------------------------------
// GERBANG AKSES TERKUNCI (Wajib Login Dulu)
// ----------------------------------------------------
Route::middleware(['auth'])->group(function () {
    
    // 🛡️ BLOCKADE 1: KHUSUS MAHASISWA
    Route::middleware(['can:access-student'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('student.home');
        Route::get('/profile', [HomeController::class, 'profileStudent'])->name('student.profile');
        Route::get('/stand/{id}', [HomeController::class, 'showStand'])->name('student.stand.detail');
        Route::get('/cart', [HomeController::class, 'cart'])->name('student.cart');
        Route::post('/cart/add/{menuId}', [HomeController::class, 'addToCart'])->name('student.cart.add');
        Route::post('/cart/remove/{id}', [HomeController::class, 'removeFromCart'])->name('student.cart.remove');
        Route::post('/checkout', [OrderController::class, 'store'])->name('student.checkout');
        Route::get('/student/track/{id}', [HomeController::class, 'trackOrder'])->name('student.order.track');
        Route::get('/orders', [HomeController::class, 'ordersHistory'])->name('orders.index');
        
        Route::get('/student/notifications', [App\Http\Controllers\Student\HomeController::class, 'notificationPage'])->name('student.notifications');
        Route::get('/student/check-notifications', [App\Http\Controllers\Student\HomeController::class, 'checkNotifications'])->name('customer.check.notifications');
    // 🛡️ BLOCKADE 2: KHUSUS PEDAGANG (STAND)
    });
    Route::middleware(['can:access-merchant'])->group(function () {
        Route::get('/merchant/home', [\App\Http\Controllers\MenuController::class, 'indexMerchant'])->name('merchant.home');
        Route::get('/merchant/menus', [\App\Http\Controllers\MenuController::class, 'listMenusMerchant'])->name('merchant.menu.index');
        Route::get('/merchant/menu/create', [\App\Http\Controllers\MenuController::class, 'create'])->name('merchant.menu.create');
        Route::post('/merchant/menu/store', [\App\Http\Controllers\MenuController::class, 'store'])->name('merchant.menu.store');
        Route::get('/merchant/menu/{id}/edit', [\App\Http\Controllers\MenuController::class, 'edit'])->name('merchant.menu.edit');
        Route::put('/merchant/menu/{id}/update', [\App\Http\Controllers\MenuController::class, 'update'])->name('merchant.menu.update');
        Route::post('/merchant/menu/toggle/{id}', [\App\Http\Controllers\MenuController::class, 'toggleStatus'])->name('merchant.menu.toggle');
        Route::delete('/merchant/menu/delete/{id}', [\App\Http\Controllers\MenuController::class, 'destroy'])->name('merchant.menu.delete');
        Route::get('/merchant/orders', [MerchantOrderController::class, 'index'])->name('merchant.orders.index');
        Route::post('/orders/{id}/update-status', [MerchantOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/merchant/stand/toggle', [\App\Http\Controllers\MenuController::class, 'toggleStandStatus'])->name('merchant.stand.toggleStatus');
        Route::get('/merchant/check-new-orders', [App\Http\Controllers\MenuController::class, 'checkNewOrders'])->name('merchant.check.new.orders');
        Route::put('/merchant/order/{id}/update', [MerchantOrderController::class, 'updateStatus'])->name('merchant.order.update-status');
    });

    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Route CRUD Stan Kuliner
        Route::get('/stands', [StandManagerController::class, 'index'])->name('stands.index');
        Route::get('/stands/create', [StandManagerController::class, 'create'])->name('stands.create');
        Route::post('/stands', [StandManagerController::class, 'store'])->name('stands.store');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/stands', [StandManagerController::class, 'index'])->name('stands.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/stands', [StandManagerController::class, 'index'])->name('stands.index');
        Route::patch('/stands/{id}', [StandManagerController::class, 'update'])->name('stands.update');
        Route::delete('/stands/{id}', [StandManagerController::class, 'destroy'])->name('stands.destroy');
    });

    // Proses Logout Web
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

});


