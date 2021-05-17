<?php

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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, "showLoginForm"])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, "login"])->name('login.post');

    Route::get('/', [App\Http\Controllers\Admin\MainController::class, "index"])->name('admin.dashboard');
});
