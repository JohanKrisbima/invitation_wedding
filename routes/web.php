<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Admin Routes Group (Strictly requires login)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Order Actions (Payment validation & invitation creation)
    Route::post('/orders/{order}/create-invitation', [InvitationController::class, 'storeFromOrder'])->name('orders.create-invitation');

    // Invitations Content Management
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/{invitation}/edit', [InvitationController::class, 'edit'])->name('invitations.edit');
    Route::put('/invitations/{invitation}/couple', [InvitationController::class, 'updateCouple'])->name('invitations.update-couple');
    Route::put('/invitations/{invitation}/events', [InvitationController::class, 'updateEvents'])->name('invitations.update-events');
    Route::post('/invitations/{invitation}/gallery', [InvitationController::class, 'uploadGallery'])->name('invitations.upload-gallery');
    Route::delete('/invitations/{invitation}/gallery/{galleryPhoto}', [InvitationController::class, 'deleteGallery'])->name('invitations.delete-gallery');
    Route::put('/invitations/{invitation}/publication', [InvitationController::class, 'updatePublication'])->name('invitations.update-publication');
});

// Root Route Redirect
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});
