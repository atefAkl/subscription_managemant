<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (available for authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard')
        ->middleware('role:admin');

    // Client Dashboard
    Route::get('/client/dashboard', [DashboardController::class, 'clientDashboard'])
        ->name('client.dashboard')
        ->middleware('role:client');

    // Admin-only routes for user management
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/register', [AuthController::class, 'showAdminRegisterForm'])->name('admin.register.form');
        Route::post('/admin/register', [AuthController::class, 'adminRegister'])->name('admin.register');
    });
});
