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
    // Auth Routes
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    // Auth Routes End

    // Main Routes
    Route::get('/', [App\Http\Controllers\Admin\MainController::class, 'index'])->name('dashboard');
    Route::get('search', [App\Http\Controllers\Admin\MainController::class, 'search'])->name('search');
    // Main Routes End

    // Dealer Routes
    Route::get('dealers', [App\Http\Controllers\Admin\DealerController::class, 'index'])->name('dealers');
    Route::get('dealer/add', [App\Http\Controllers\Admin\DealerController::class, 'create'])->name('dealer.add');
    Route::post('dealer/add', [App\Http\Controllers\Admin\DealerController::class, 'store'])->name('dealer.add.post');
    Route::get('dealer/edit/{dealer}', [App\Http\Controllers\Admin\DealerController::class, 'edit'])->name('dealer.edit');
    Route::put('dealer/edit/{dealer}', [App\Http\Controllers\Admin\DealerController::class, 'update'])->name('dealer.edit.put');
    // Dealer Routes End

    // Staff Routes
    Route::get('staffs', [App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staffs');
    Route::get('staff/add', [App\Http\Controllers\Admin\StaffController::class, 'create'])->name('staff.add');
    Route::post('staff/add', [App\Http\Controllers\Admin\StaffController::class, 'store'])->name('staff.add.post');
    Route::get('staff/edit/{staff}', [App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('staff.edit');
    Route::put('staff/edit/{staff}', [App\Http\Controllers\Admin\StaffController::class, 'update'])->name('staff.edit.put');
    // Staff Routes End

    // User Routes
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::get('user/add', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('user.add');
    Route::post('user/add', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('user.add.post');
    Route::get('user/edit/{user}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('user.edit');
    Route::put('user/edit/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('user.edit.put');
    Route::delete('user/delete/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('user.delete');
    // User Routes End

    //  Customer Routes
    Route::get('customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers');
    Route::get('customer/add', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customer.add');
    Route::post('customer/add', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customer.add.post');
    Route::get('customer/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customer.show');
    Route::get('customer/edit/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('customer/edit/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customer.edit.put');
    Route::put('customer/approve/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'approve'])->name('customer.approve.post');
    //  Customer Routes End

    // Contract Type Routes
    Route::get('contract-types', [App\Http\Controllers\Admin\ContractTypeController::class, 'index'])->name('contract.types');
    Route::get('contract-type/add', [App\Http\Controllers\Admin\ContractTypeController::class, 'create'])->name('contract.type.add');
    Route::post('contract-type/add', [App\Http\Controllers\Admin\ContractTypeController::class, 'store'])->name('contract.type.add.post');
    Route::get('contract-type/edit/{contractType}', [App\Http\Controllers\Admin\ContractTypeController::class, 'edit'])->name('contract.type.edit');
    Route::put('contract-type/edit/{contractType}', [App\Http\Controllers\Admin\ContractTypeController::class, 'update'])->name('contract.type.edit.put');
    // Contract Type Routes End

    // Category Routes
    Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories');
    Route::get('category/add', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('category.add');
    Route::post('category/add', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('category.add.post');
    Route::get('category/edit/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/edit/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('category.edit.put');
    // Category Routes End

    // Product Routes
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products');
    Route::get('product/add', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('product.add');
    Route::post('product/add', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('product.add.post');
    Route::get('product/edit/{product}', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('product.edit');
    Route::put('product/edit/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('product.edit.put');
    Route::delete('product/delete/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('product.delete');
    // Product Routes End

    // Service Routes
    Route::get('services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services');
    Route::get('service/add', [App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('service.add');
    Route::post('service/add', [App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('service.add.post');
    Route::get('service/edit/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('service.edit');
    Route::put('service/edit/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('service.edit.put');
    // Service Routes End

    // Message Routes
    Route::get('messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages');
    Route::get('message/add', [App\Http\Controllers\Admin\MessageController::class, 'create'])->name('message.add');
    Route::post('message/add', [App\Http\Controllers\Admin\MessageController::class, 'store'])->name('message.add.post');
    Route::get('message/edit/{message}', [App\Http\Controllers\Admin\MessageController::class, 'edit'])->name('message.edit');
    Route::put('message/edit/{message}', [App\Http\Controllers\Admin\MessageController::class, 'update'])->name('message.edit.put');
    // Message Routes End

    // Subscription Routes
    Route::get('subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions');
    Route::get('subscription/add', [App\Http\Controllers\Admin\SubscriptionController::class, 'create'])->name('subscription.add');
    Route::post('subscription/add', [App\Http\Controllers\Admin\SubscriptionController::class, 'store'])->name('subscription.add.post');
    Route::get('subscription/edit/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'edit'])->name('subscription.edit');
    Route::put('subscription/edit/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'update'])->name('subscription.edit.put');
    Route::delete('subscription/delete/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'destroy'])->name('subscription.delete');
    Route::put('subscription/approve/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'approve'])->name('subscription.approve.post');
    Route::put('subscription/unapprove/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'unApprove'])->name('subscription.unapprove.post');
    Route::get('subscription/{subscription}/payments', [App\Http\Controllers\Admin\SubscriptionController::class, 'payments'])->name('subscription.payments');
    Route::put('subscription/price/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'price'])->name('subscription.price');
    Route::get('subscription/change/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'change'])->name('subscription.change');
    Route::put('subscription/change/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'upgrade'])->name('subscription.change.put');
    Route::put('subscription/cancel/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('subscription.cancel.put');
    // Subscription Routes End

    // Subscription Routes
    Route::post('payment/received/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'received'])->name('payment.received.post');
    Route::post('payment/result/{payment?}', [App\Http\Controllers\Admin\PaymentController::class, 'payment_result'])->name('payment.result');
    Route::put('payment/price/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'price'])->name('payment.price.put');
    // Subscription Routes End
});

// Request Routes
// Get district by city id
Route::get('getDistricts/{id}', [App\Http\Controllers\CityController::class, 'districts'])->name('get.district')->where('id', '[0-9]+');
// Request Routes End
