<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuestbookController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\HomeController;
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

    // Templates Catalog Management
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
    Route::put('/templates/{template}', [TemplateController::class, 'update'])->name('templates.update');
    Route::post('/templates/{template}/toggle-status', [TemplateController::class, 'toggleStatus'])->name('templates.toggle-status');
    Route::delete('/templates/{template}', [TemplateController::class, 'destroy'])->name('templates.destroy');

    // Orders Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
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

    // Guest Management
    Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
    Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
    Route::put('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
    Route::delete('/guests/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
    Route::post('/guests/{guest}/toggle-sent', [GuestController::class, 'toggleSent'])->name('guests.toggle-sent');
    Route::post('/guests/import', [GuestController::class, 'import'])->name('guests.import');
    Route::post('/guests/import-chunk', [GuestController::class, 'importChunk'])->name('guests.import-chunk');
    Route::get('/guests/export-data', [GuestController::class, 'exportData'])->name('guests.export-data');
    Route::get('/guests/export', [GuestController::class, 'exportFile'])->name('guests.export');
    Route::get('/guests/template-download', [GuestController::class, 'downloadTemplate'])->name('guests.download-template');

    // Guestbook & RSVP Interactions Management
    Route::get('/guestbook', [GuestbookController::class, 'index'])->name('guestbook.index');
    Route::post('/guestbook/messages/{message}/toggle-approval', [GuestbookController::class, 'toggleApproval'])->name('guestbook.toggle-approval');
    Route::delete('/guestbook/messages/{message}', [GuestbookController::class, 'destroyMessage'])->name('guestbook.destroy-message');
    Route::delete('/guestbook/rsvps/{rsvp}', [GuestbookController::class, 'destroyRsvp'])->name('guestbook.destroy-rsvp');
    Route::get('/guestbook/export-rsvp-data', [GuestbookController::class, 'exportRsvpData'])->name('guestbook.export-rsvp-data');
    Route::get('/guestbook/export-messages-data', [GuestbookController::class, 'exportMessagesData'])->name('guestbook.export-messages-data');
});

// Public Landing Page (Lumina Invites)
Route::get('/', [HomeController::class, 'index'])->name('home');
