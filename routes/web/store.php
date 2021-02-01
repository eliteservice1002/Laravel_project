<?php

use App\Domains\Core\Http\Controllers\HomeController;
use App\Domains\Core\Http\Livewire\Gateway;
use App\Domains\Marketing\ResolveSlugAction;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', [HomeController::class,'home']);

Route::get('/{locale}', Gateway::class);

Route::prefix('/{locale}/store')->group(function () {
    // Route::get('/', [HomeController::class,'home'])
    //     ->name('home');

    Route::get('l/{link}', ResolveSlugAction::class)
        ->name('slug.resolve')
        ->where('link', '.*');

    Route::get('livewire', [HomeController::class, 'livewire'])
        ->name('livewire');

    // Route::get('search', \App\Domains\Core\Http\Controllers\HomeController::class.'@search');

    // Route::get('debug', \App\Domains\Core\Http\Controllers\HomeController::class.'@debug');
});
