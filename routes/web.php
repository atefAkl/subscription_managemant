<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Client\SubscriptionController;
use App\Http\Controllers\Client\DeviceController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\StatisticsController;
use App\Http\Controllers\Admin\SubscriptionRequestController;
use App\Http\Controllers\Admin\DeviceController as AdminDeviceController;
use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Test iPhone System Page
Route::get('/test-iphone-system', function () {
    return view('test-iphone-system');
})->name('test.iphone.system');

// Simple Test Page
Route::get('/test-simple', function () {
    return view('test-simple');
})->name('test.simple');

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

    // Admin-only routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::get('/register', [AuthController::class, 'showAdminRegisterForm'])->name('register.form');
        Route::post('/register', [AuthController::class, 'adminRegister'])->name('register');

        // Subscription Requests Management
        Route::get('/subscription-requests', [SubscriptionRequestController::class, 'index'])->name('subscription-requests.index');
        Route::get('/subscription-requests/{id}', [SubscriptionRequestController::class, 'show'])->name('subscription-requests.show');
        Route::get('/subscription-requests/{id}/quote', [SubscriptionRequestController::class, 'showQuoteForm'])->name('subscription-requests.quote');
        Route::post('/subscription-requests/{id}/quote', [SubscriptionRequestController::class, 'sendQuote'])->name('subscription-requests.quote.send');
        Route::post('/subscription-requests/{id}/reject', [SubscriptionRequestController::class, 'reject'])->name('subscription-requests.reject');

        // Payments Management
        Route::get('/payments/pending', [SubscriptionRequestController::class, 'pendingPayments'])->name('payments.pending');
        Route::post('/payments/{payment}/verify', [SubscriptionRequestController::class, 'verifyPayment'])->name('payments.verify');

        // Devices Management
        Route::get('/devices', [AdminDeviceController::class, 'index'])->name('devices.index');
        Route::get('/devices/pending', [AdminDeviceController::class, 'pending'])->name('devices.pending');
        Route::get('/devices/{id}', [AdminDeviceController::class, 'show'])->name('devices.show');
        Route::get('/devices/{id}/activate', [AdminDeviceController::class, 'showActivationForm'])->name('devices.activate');
        Route::post('/devices/{id}/activate', [AdminDeviceController::class, 'activate'])->name('devices.activate.process');
        Route::put('/devices/{id}', [AdminDeviceController::class, 'update'])->name('devices.update');
        Route::post('/devices/{id}/suspend', [AdminDeviceController::class, 'suspend'])->name('devices.suspend');
        Route::post('/devices/{id}/reactivate', [AdminDeviceController::class, 'reactivate'])->name('devices.reactivate');
        Route::post('/devices/{id}/regenerate-token', [AdminDeviceController::class, 'regenerateToken'])->name('devices.regenerate-token');
        Route::delete('/devices/{id}', [AdminDeviceController::class, 'destroy'])->name('devices.destroy');
    });

    // Client-only routes for features
    Route::middleware('role:client')->prefix('client')->name('client.')->group(function () {
        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
        Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::get('/subscriptions/{id}/devices', [SubscriptionController::class, 'manageDevices'])->name('subscriptions.devices');
        Route::post('/subscriptions/{id}/devices', [SubscriptionController::class, 'addDevice'])->name('subscriptions.devices.add');

        // Subscription Requests
        Route::get('/subscription-requests/{id}', [SubscriptionController::class, 'showRequest'])->name('subscription-requests.show');
        Route::get('/subscription-requests/{id}/payment', [SubscriptionController::class, 'showPayment'])->name('subscription-requests.payment');
        Route::post('/subscription-requests/{id}/payment', [SubscriptionController::class, 'processPayment'])->name('subscription-requests.process-payment');

        // Customize Subscription & Device Management
        Route::get('/subscription-requests/{id}/customize', [SubscriptionController::class, 'showCustomization'])->name('subscription-requests.customize');
        Route::post('/subscription-requests/{id}/customize', [SubscriptionController::class, 'updateCustomization'])->name('subscription-requests.customize.update');
        Route::post('/subscription-requests/{id}/devices/add', [SubscriptionController::class, 'addDeviceToRequest'])->name('subscription-requests.devices.add');

        // Device Management
        Route::get('/devices', [DeviceController::class, 'index'])->name('devices');
        Route::put('/devices/{device}/update-name', [DeviceController::class, 'updateName'])->name('devices.update-name');
        Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');

        // Payments & Bills
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
        Route::get('/payments/bill-details/{billId}', [PaymentController::class, 'billDetails'])->name('payments.bill-details');
        Route::get('/payments/receipt/{paymentId}', [PaymentController::class, 'paymentReceipt'])->name('payments.receipt');

        // Statistics
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    });
});
