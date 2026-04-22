<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// =============================================
// PUBLIC ROUTES
// =============================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Order
Route::get('/order', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

// Tracking
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

// Chat
Route::get('/chat', [ChatController::class, 'index'])->name('chat');

// =============================================
// AUTH ROUTES
// =============================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// =============================================
// DASHBOARD ROUTES (Auth Required)
// =============================================

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {

    // Redirect based on role
    Route::get('/', [DashboardController::class, 'redirect']);

    // ADMIN ROUTES
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'admin'])->name('index');
        Route::get('/orders', [DashboardController::class, 'adminOrders'])->name('orders');
        Route::get('/orders/{order}', [DashboardController::class, 'adminOrderDetail'])->name('orders.detail');
        Route::patch('/orders/{order}/update-status', [DashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
        Route::get('/jastipers', [DashboardController::class, 'adminJastipers'])->name('jastipers');
        Route::patch('/jastipers/{jastiper}/toggle', [DashboardController::class, 'toggleJastiper'])->name('jastipers.toggle');
        Route::get('/users', [DashboardController::class, 'adminUsers'])->name('users');
        Route::get('/reports', [DashboardController::class, 'adminReports'])->name('reports');
        Route::get('/settings', [DashboardController::class, 'adminSettings'])->name('settings');
    });

    // JASTIPER ROUTES
    Route::middleware('role:jastiper')->prefix('jastiper')->name('jastiper.')->group(function () {
        Route::get('/', [DashboardController::class, 'jastiper'])->name('index');
        Route::get('/orders', [DashboardController::class, 'jastiperOrders'])->name('orders');
        Route::get('/history', [DashboardController::class, 'jastiperHistory'])->name('history');
        Route::get('/earnings', [DashboardController::class, 'jastiperEarnings'])->name('earnings');
        Route::get('/profile', [DashboardController::class, 'jastiperProfile'])->name('profile');
        Route::post('/toggle-status', [DashboardController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/orders/{order}/accept', [DashboardController::class, 'acceptOrder'])->name('orders.accept');
        Route::patch('/orders/{order}/reject', [DashboardController::class, 'rejectOrder'])->name('orders.reject');
        Route::patch('/orders/{order}/update-status', [DashboardController::class, 'jastiperUpdateStatus'])->name('orders.update-status');
    });

});
