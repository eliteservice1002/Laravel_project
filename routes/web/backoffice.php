<?php

use App\Domains\Core\Http\Controllers\HomeController;
use App\Domains\Tenants\Http\NovaAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Controllers\ForgotPasswordController;
use Laravel\Nova\Http\Controllers\LoginController;
use Laravel\Nova\Http\Controllers\RouterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/login/google', [NovaAuthController::class, 'redirectToGoogle'])
    ->name('login.google');

Route::get('/login/google/callback', [NovaAuthController::class, 'processGoogleCallback'])
    ->name('login.google_callback');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, '@showResetForm'])
    ->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset']);

Route::middleware('nova')->group(function (): void {
    Route::get('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/', [RouterController::class, 'show'])
        ->name('index');

    Route::get('/admin/auth/zoho', [HomeController::class, 'zoho'])
        ->name('auth.zoho');
    Route::get('/admin/auth/zoho_callback', [HomeController::class, 'zohoCallback'])
        ->name('auth.zoho_callback');
    Route::get('/admin/auth/zoho_data', [HomeController::class, 'zohoData'])
        ->name('auth.zoho_data');

    Route::get('/{view}', [RouterController::class, 'show'])
        ->name('router')
        ->where('view', '.*');
});
