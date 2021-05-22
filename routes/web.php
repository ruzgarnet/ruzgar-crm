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

Route::prefix('admin')->middleware('admin.middleware')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::get('/', [App\Http\Controllers\Admin\MainController::class, 'index'])->name('dashboard');

    Route::get('dealers', [App\Http\Controllers\Admin\DealerController::class, 'index'])->name('dealers');
    Route::get('dealer/add', [App\Http\Controllers\Admin\DealerController::class, 'create'])->name('dealer.add');
    Route::post('dealer/add', [App\Http\Controllers\Admin\DealerController::class, 'store'])->name('dealer.add.post');
    Route::get('dealer/edit/{dealer}', [App\Http\Controllers\Admin\DealerController::class, 'edit'])->name('dealer.edit');
    Route::put('dealer/edit/{dealer}', [App\Http\Controllers\Admin\DealerController::class, 'update'])->name('dealer.edit.put');
    Route::delete('dealer/delete/{dealer}', [App\Http\Controllers\Admin\DealerController::class, 'destroy'])->name('dealer.delete');

    Route::get('staffs', [App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staffs');
    Route::get('staff/add', [App\Http\Controllers\Admin\StaffController::class, 'create'])->name('staff.add');
    Route::post('staff/add', [App\Http\Controllers\Admin\StaffController::class, 'store'])->name('staff.add.post');
    Route::get('staff/edit/{staff}', [App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('staff/edit/{staff}', [App\Http\Controllers\Admin\StaffController::class, 'update'])->name('staff.edit.put');

    Route::get('customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers');
    Route::get('customer/add', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customer.add');
    Route::post('customer/add', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customer.add.post');
    Route::get('customer/edit/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/edit/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customer.edit.put');
    Route::put('customer/approve/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'approve'])->name('customer.approve.post');

    Route::get('contract-types', [App\Http\Controllers\Admin\ContractTypeController::class, 'index'])->name('contract.types');
    Route::get('contract-type/add', [App\Http\Controllers\Admin\ContractTypeController::class, 'create'])->name('contract.type.add');
    Route::post('contract-type/add', [App\Http\Controllers\Admin\ContractTypeController::class, 'store'])->name('contract.type.add.post');
    Route::get('contract-type/edit/{contractType}', [App\Http\Controllers\Admin\ContractTypeController::class, 'edit'])->name('contract.type.edit');
    Route::put('contract-type/edit/{contractType}', [App\Http\Controllers\Admin\ContractTypeController::class, 'update'])->name('contract.type.edit.put');

<<<<<<< HEAD
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products');
    Route::get('product/add', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('product.add');
    Route::post('product/add', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('product.add.post');
    Route::get('product/edit/{product}', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('product.edit');
    Route::put('product/edit/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('product.edit.put');
    Route::delete('product/delete/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('product.delete');
=======
    Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories');
    Route::get('category/add', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('category.add');
    Route::post('category/add', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('category.add.post');
    Route::get('category/edit/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/edit/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('category.edit.put');
>>>>>>> 1586c165f2ee595bd1c30df2ec993f68759631c6
});

Route::get('getDistricts/{id}', [App\Http\Controllers\CityController::class, 'districts'])->name('get.district')->where('id', '[0-9]+');
