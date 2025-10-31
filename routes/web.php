<?php

use App\Http\Controllers\Admin\ClientManagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Client\SubscriptionController;
use App\Http\Controllers\Client\DeviceController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\StatisticsController;
use App\Http\Controllers\Admin\SubscriptionRequestController;
use App\Http\Controllers\Admin\DeviceController as AdminDeviceController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Test iPhone System Page
Route::get('/test-iphone-system', function () {
    return view('test-iphone-system');
})->name('test.iphone.system');

// Test Payment Page
Route::get('/test-payment', function () {
    return view('test-payment');
})->name('test.payment');

// Ajax Test Page
Route::get('/ajax-test', function () {
    return view('ajax-test');
})->name('ajax.test')->middleware('auth');

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
        // User management (legacy)
        Route::get('/register', [AuthController::class, 'showAdminRegisterForm'])->name('register.form');
        Route::post('/register', [AuthController::class, 'adminRegister'])->name('register');

        // Admin Management Module (Admins only)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',                     [UserManagementController::class, 'index'])->name('index');
            Route::get('/create',               [UserManagementController::class, 'create'])->name('create');
            Route::post('/',                    [UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}',               [UserManagementController::class, 'show'])->name('show');
            Route::get('/{user}/edit',          [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}',               [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}',            [UserManagementController::class, 'destroy'])->name('destroy');
            Route::get('/{user}/activities',    [UserManagementController::class, 'activities'])->name('activities');
            Route::get('/{user}/permissions',   [UserManagementController::class, 'permissions'])->name('permissions');
        });

        // Client Management Module (Clients only)
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/',                                         [ClientManagementController::class, 'index'])->name('index');
            Route::get('/create',                                   [ClientManagementController::class, 'create'])->name('create');
            Route::post('/',                                        [ClientManagementController::class, 'store'])->name('store');
            Route::get('/{client}',                                 [ClientManagementController::class, 'show'])->name('show');
            Route::get('/{client}/edit',                            [ClientManagementController::class, 'edit'])->name('edit');
            Route::put('/{client}',                                 [ClientManagementController::class, 'update'])->name('update');
            Route::delete('/{client}',                              [ClientManagementController::class, 'destroy'])->name('destroy');
            Route::get('/{client}/activities',                      [ClientManagementController::class, 'activities'])->name('activities');
            Route::post('/{client}/renew-subscription',             [ClientManagementController::class, 'renewSubscription'])->name('renew-subscription');
            Route::post('/{client}/add-device',                     [ClientManagementController::class, 'addDevice'])->name('add-device');
            Route::delete('/{client}/remove-device/{device}',       [ClientManagementController::class, 'removeDevice'])->name('remove-device');
            Route::patch('/{client}/toggle-device-status/{device}', [ClientManagementController::class, 'toggleDeviceStatus'])->name('toggle-device-status');
            Route::get('/{client}/device-details/{device}',         [ClientManagementController::class, 'getDeviceDetails'])->name('device-details');
            Route::post('/{client}/toggle-subscription',            [ClientManagementController::class, 'toggleSubscription'])->name('toggle-subscription');
            // Additional routes for subscription management within clients
            Route::post('/{client}/update-subscription',            [ClientManagementController::class, 'updateSubscription'])->name('update-subscription');
            Route::post('/{client}/create-subscription',            [ClientManagementController::class, 'createSubscription'])->name('create-subscription');
            Route::delete('/{client}/devices/{device}',             [ClientManagementController::class, 'deleteDevice'])->name('devices.destroy');
            Route::post('/{client}/devices/{device}/toggle',        [ClientManagementController::class, 'toggleDeviceStatus'])->name('devices.toggle');
            Route::get('/statistics/dashboard',                     [ClientManagementController::class, 'statistics'])->name('statistics');
        });

        // System Settings Module
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('index');
            Route::put('/update', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('update');
            Route::post('/clear-cache', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'clearCache'])->name('clear-cache');
            Route::post('/toggle-maintenance', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
            Route::post('/create-backup', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'createBackup'])->name('create-backup');
            Route::get('/health-check', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'healthCheck'])->name('health-check');
            Route::get('/system-stats', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'getSystemStats'])->name('system-stats');
        });

        // Statistics Module
        Route::prefix('statistics')->name('statistics.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admin\StatisticsController::class, 'exportReport'])->name('export');
            Route::get('/real-time', [\App\Http\Controllers\Admin\StatisticsController::class, 'getRealTimeStats'])->name('real-time');
        });

        // Subscription Requests Management
        Route::get('/subscription-requests', [SubscriptionRequestController::class, 'index'])->name('subscription-requests.index');
        Route::get('/subscription-requests/{id}', [SubscriptionRequestController::class, 'show'])->name('subscription-requests.show');
        Route::get('/subscription-requests/{id}/quote', [SubscriptionRequestController::class, 'showQuoteForm'])->name('subscription-requests.quote');
        Route::post('/subscription-requests/{id}/quote', [SubscriptionRequestController::class, 'sendQuote'])->name('subscription-requests.quote.send');
        Route::post('/subscription-requests/{id}/reject', [SubscriptionRequestController::class, 'reject'])->name('subscription-requests.reject');

        // Payments Management
        Route::get('/payments/pending', [SubscriptionRequestController::class, 'pendingPayments'])->name('payments.pending');
        Route::post('/payments/{payment}/verify', [SubscriptionRequestController::class, 'verifyPayment'])->name('payments.verify')->middleware('web');
        Route::post('/payments/{payment}/reject', [SubscriptionRequestController::class, 'rejectPayment'])->name('payments.reject')->middleware('web');
        Route::get('/payments/{payment}/details', [SubscriptionRequestController::class, 'paymentDetails'])->name('payments.details');

        // Subscriptions Management
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'store'])->name('store');
            Route::get('/{subscription}', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'show'])->name('show');
            Route::get('/{subscription}/edit', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'edit'])->name('edit');
            Route::put('/{subscription}', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'update'])->name('update');
            Route::post('/{subscription}/activate', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'activate'])->name('activate');
            Route::post('/{subscription}/suspend', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'suspend'])->name('suspend');
            Route::post('/{subscription}/renew', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'renew'])->name('renew');
            Route::delete('/{subscription}', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'destroy'])->name('destroy');

            // Device management within subscription
            Route::prefix('{subscription}/devices')->name('devices.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'devices'])->name('index');
                Route::post('/', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'addDevice'])->name('store');
                Route::put('/{device}', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'updateDevice'])->name('update');
                Route::post('/{device}/activate', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'activateDevice'])->name('activate');
                Route::post('/{device}/suspend', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'suspendDevice'])->name('suspend');
                Route::delete('/{device}', [App\Http\Controllers\Admin\SubscriptionManagementController::class, 'removeDevice'])->name('destroy');
            });
        });

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
