<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminWorkspaceController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('robots');
Route::get('/research', [SiteController::class, 'research'])->name('research');
Route::get('/reports/{report:slug}', [SiteController::class, 'report'])->name('report');
Route::get('/stocks/{stock:symbol}', [SiteController::class, 'stock'])->name('stock');
Route::get('/insights', [SiteController::class, 'editorial'])->name('editorial');
Route::get('/insights/{article:slug}', [SiteController::class, 'article'])->name('article');
Route::post('/enquiries', [SiteController::class, 'lead'])->middleware('throttle:10,1')->name('lead');
Route::get('/sample-report', [SiteController::class, 'sample'])->name('sample');
Route::get('/media/{filename}', [AdminWorkspaceController::class, 'mediaFile'])->name('media.file');
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth', ['mode' => 'login'])->name('login');
    Route::view('/register', 'auth', ['mode' => 'register'])->name('register');
    Route::view('/forgot-password', 'auth', ['mode' => 'forgot'])->name('password.request');
    Route::get('/reset-password/{token}', fn (string $token) => view('auth', ['mode' => 'reset', 'token' => $token]))->name('password.reset');
    Route::post('/login', [AccountController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/register', [AccountController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/forgot-password', [AccountController::class, 'forgot'])->middleware('throttle:3,1')->name('password.email');
    Route::post('/reset-password', [AccountController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/account/{tab}', [AccountController::class, 'dashboard'])->name('account');
    Route::post('/holdings', [AccountController::class, 'holding'])->name('holding');
    Route::post('/watchlist', [AccountController::class, 'watch'])->name('watch');
    Route::delete('/account/{type}/{id}', [AccountController::class, 'remove'])->name('remove');
    Route::post('/subscribe', [AccountController::class, 'subscribe'])->name('subscribe');
    Route::middleware('can:admin')->group(function () {
        Route::get('/admin', [AdminWorkspaceController::class, 'overview'])->name('admin');
        Route::get('/admin/settings', [AdminWorkspaceController::class, 'settings'])->name('admin.settings');
        Route::put('/admin/settings', [AdminWorkspaceController::class, 'saveSettings'])->name('admin.settings.save');
        Route::get('/admin/activity', [AdminWorkspaceController::class, 'activity'])->name('admin.activity');
        Route::get('/admin/media', [AdminWorkspaceController::class, 'media'])->name('admin.media');
        Route::post('/admin/media', [AdminWorkspaceController::class, 'upload'])->name('admin.media.upload');
        Route::put('/admin/media/{id}', [AdminWorkspaceController::class, 'updateMedia'])->whereNumber('id')->name('admin.media.update');
        Route::post('/admin/sections/{id}/reorder', [AdminWorkspaceController::class, 'reorder'])->name('admin.reorder');
        Route::get('/admin/manage/{resource}', [AdminWorkspaceController::class, 'index'])->name('admin.records');
        Route::get('/admin/manage/{resource}/create', [AdminWorkspaceController::class, 'edit'])->name('admin.create');
        Route::get('/admin/manage/{resource}/{id}/edit', [AdminWorkspaceController::class, 'edit'])->whereNumber('id')->name('admin.edit');
        Route::post('/admin/manage/{resource}', [AdminWorkspaceController::class, 'save'])->name('admin.store');
        Route::put('/admin/manage/{resource}/{id}', [AdminWorkspaceController::class, 'save'])->whereNumber('id')->name('admin.update');
        Route::delete('/admin/manage/{resource}/{id}', [AdminWorkspaceController::class, 'delete'])->whereNumber('id')->name('admin.destroy');
        Route::get('/admin/studio', [AdminController::class, 'index'])->name('admin.studio');
        Route::post('/admin/{type}', [AdminController::class, 'save'])->whereIn('type', ['report', 'article'])->name('admin.save');
        Route::delete('/admin/{type}/{id}', [AdminController::class, 'delete'])->whereIn('type', ['report', 'article'])->whereNumber('id')->name('admin.delete');
    });
});
Route::get('/{page}', [SiteController::class, 'page'])->name('page');
