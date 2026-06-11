<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminRefundController;
use App\Http\Controllers\Admin\AdminPaymentMethodController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\StorageFileController;

Route::get('/', fn () => redirect()->route('login'));

// Serve uploaded files from `storage/app/public` via Laravel.
// Useful when `public/storage` symlink is missing/broken.
Route::get('/storage/{path}', [StorageFileController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.serve');

Route::get('/home', [EventController::class, 'index'])->name('home');
Route::get('/events/{id}', [EventController::class, 'show'])->name('event.detail');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('forgot-password');
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp'])->name('forgot-password.send');
Route::get('/reset-password', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset.post');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::get('/admin/forgot-password', fn () => redirect()->route('forgot-password', ['context' => 'organization']))
    ->name('admin.forgot-password');
Route::middleware('guest')->group(function () {
    Route::get('/admin/register', [AuthController::class, 'showAdminRegister'])->name('admin.register');
    Route::post('/admin/register', [AuthController::class, 'adminRegister'])->name('admin.register.post');
});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/events/{id}/register', [EventRegistrationController::class, 'showForm'])->name('event.register.form');
    Route::post('/events/{id}/register', [EventRegistrationController::class, 'register'])->name('event.register.post');

    Route::get('/my-activities', [EventRegistrationController::class, 'myRegistrations'])->name('user.activities.status');
    Route::get('/past-events', [UserDashboardController::class, 'pastEvents'])->name('user.past.events');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile.history');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/payment-history', [PaymentController::class, 'history'])->name('user.payment.history');
    Route::post('/payments/{id}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.upload.proof');

    Route::get('/refund-request', [RefundController::class, 'showForm'])->name('refund.request');
    Route::post('/refund-request', [RefundController::class, 'store'])->name('refund.store');
    Route::get('/refund-status', [RefundController::class, 'status'])->name('refund.status');
});

Route::middleware(['auth', 'organization'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminEventController::class, 'dashboard'])->name('dashboard');

    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::put('/events/{id}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
    Route::post('/registrations/{id}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{id}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');

    Route::get('/refund', [AdminRefundController::class, 'index'])->name('refunds.index');
    Route::post('/refund/{id}/approve', [AdminRefundController::class, 'approve'])->name('refunds.approve');
    Route::post('/refund/{id}/reject', [AdminRefundController::class, 'reject'])->name('refunds.reject');

    Route::get('/payment-methods', [AdminPaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/payment-methods', [AdminPaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::put('/payment-methods/{id}', [AdminPaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{id}', [AdminPaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
Route::get('/organizations/{organization}/events', [OrganizationController::class, 'events'])->name('organization.events');
Route::get('/terms', fn () => view('terms'))->name('terms');
