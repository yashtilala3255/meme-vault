<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ✅ Auth Routes (from Laravel Breeze)
require __DIR__.'/auth.php';

// ============================================
// PUBLIC ROUTES
// ============================================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Templates
Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::get('/templates/random', [TemplateController::class, 'random'])->name('templates.random');
Route::get('/templates/{template:slug}', [TemplateController::class, 'show'])->name('templates.show');
Route::post('/templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');

// Categories
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Editor
Route::get('/editor/{template:slug}', [EditorController::class, 'edit'])->name('editor.edit');
Route::post('/editor/{template}/save', [EditorController::class, 'save'])->name('editor.save');

// Pricing
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Support (public)
Route::get('/support', [SupportController::class, 'index'])->name('support');
Route::post('/support/ticket', [SupportController::class, 'store'])->name('support.store');

// ============================================
// AUTHENTICATED ROUTES
// ============================================

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Subscription
    Route::get('/my-subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscribe/{tier}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Support (authenticated)
    Route::get('/support/ticket/{ticket}', [SupportController::class, 'show'])->name('support.show');
    Route::post('/support/ticket/{ticket}/reply', [SupportController::class, 'reply'])->name('support.reply');
});

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

Route::get('/image-proxy', [App\Http\Controllers\EditorController::class, 'proxyImage'])->name('image.proxy');

Route::get('/templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');