<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API Routes
Route::post('payment/get', [App\Http\Controllers\APIController::class, 'get_payment_list'])->name('get.payment.list');
Route::post('payment/pay', [App\Http\Controllers\APIController::class, 'pay'])->name('payment.pay');
Route::post('login', [App\Http\Controllers\APIController::class, 'login'])->name('api.login');
Route::post('add_fault', [App\Http\Controllers\APIController::class, 'add_fault'])->name('api.add.fault');
Route::post('get_reference_code', [App\Http\Controllers\APIController::class, 'get_reference_code'])->name('api.get.reference_code');
Route::post('get_faults', [App\Http\Controllers\APIController::class, 'get_faults'])->name('api.get.faults');
Route::post('get_fault', [App\Http\Controllers\APIController::class, 'get_fault'])->name('api.get.fault');
Route::post('edit_fault', [App\Http\Controllers\APIController::class, 'edit_fault'])->name('api.edit.fault');
Route::post('search_fault', [App\Http\Controllers\APIController::class, 'search_fault'])->name('api.search.fault');
Route::post('add_application', [App\Http\Controllers\APIController::class, 'add_application'])->name('api.add.application');
Route::post('get_fault_with_serial_number', [App\Http\Controllers\APIController::class, 'get_fault_with_serial_number'])->name('api.get.fault_with_serial_number');
Route::post('send_sms', [App\Http\Controllers\APIController::class, 'send_sms'])->name('api.send_sms');
// API Routes End
