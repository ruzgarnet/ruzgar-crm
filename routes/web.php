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

Route::middleware('admin.middleware')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    // Auth Routes End

    // Main Routes
    Route::get('/', [App\Http\Controllers\Admin\MainController::class, 'index'])->name('dashboard');
    Route::get('search', [App\Http\Controllers\Admin\MainController::class, 'search'])->name('search');
    Route::get('infrastructure', [App\Http\Controllers\Admin\MainController::class, 'infrastructure'])->name('infrastructure');
    Route::post('payment/pre/auth/{payment}', [App\Http\Controllers\Admin\MainController::class, 'create_pre_auth'])->name('payment.pre.auth.create');
    Route::post('payment/pre/auth/result/{moka_log}', [App\Http\Controllers\Admin\MainController::class, 'payment_pre_auth_result'])->name('payment.pre.auth.result');
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
    Route::get('message/send', [App\Http\Controllers\Admin\MessageController::class, 'send'])->name('message.send');
    Route::post('message/send', [App\Http\Controllers\Admin\MessageController::class, 'submit'])->name('message.send.post');
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
    Route::get('subscription/contract/preview/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'preview'])->name('subscription.contract');
    Route::put('subscription/freeze/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'freeze'])->name('subscription.freeze.put');
    Route::put('subscription/unfreeze/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'unFreeze'])->name('subscription.unfreeze.put');
    Route::put('subscription/cancel_auto_payment/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'cancel_auto_payment'])->name('subscription.cancel_auto_payment');
    // Subscription Routes End

    // Payment Routes
    Route::post('payment/received/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'received'])->name('payment.received.post');
    Route::put('payment/price/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'price'])->name('payment.price.put');
    Route::get('payment/auto/request/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'send_auto'])->name('payment.auto.request');
    Route::post('payment/add/{subscription}', [App\Http\Controllers\Admin\PaymentController::class, 'store'])->name('subscription.payment.create');
    Route::post('payment/cancel/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'cancel'])->name('subscription.payment.cancel');
    Route::delete('payment/delete/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'destroy'])->name('subscription.payment.delete');
    // Payment Routes End

    // Reference Routes
    Route::get('references', [App\Http\Controllers\Admin\ReferenceController::class, 'index'])->name('references');
    Route::get('reference/{subscription}', [App\Http\Controllers\Admin\ReferenceController::class, 'create'])->name('reference.add');
    Route::post('reference/{subscription}', [App\Http\Controllers\Admin\ReferenceController::class, 'store'])->name('reference.add.post');
    Route::put('reference/edit/{reference}', [App\Http\Controllers\Admin\ReferenceController::class, 'update'])->name('reference.edit.put');
    // Reference Routes

    // Fault Record Routes
    Route::get('fault/records', [App\Http\Controllers\Admin\FaultRecordController::class, 'index'])->name('fault.records');
    Route::get('fault/record/add', [App\Http\Controllers\Admin\FaultRecordController::class, 'create'])->name('fault.record.add');
    Route::post('fault/record/add', [App\Http\Controllers\Admin\FaultRecordController::class, 'store'])->name('fault.record.add.post');
    Route::get('fault/record/edit/{faultRecord}', [App\Http\Controllers\Admin\FaultRecordController::class, 'edit'])->name('fault.record.edit');
    Route::put('fault/record/edit/{faultRecord}', [App\Http\Controllers\Admin\FaultRecordController::class, 'update'])->name('fault.record.edit.put');
    // Fault Record Routes End

    // Fault Type Routes
    Route::get('fault/types', [App\Http\Controllers\Admin\FaultTypeController::class, 'index'])->name('fault.types');
    Route::get('fault/type/add', [App\Http\Controllers\Admin\FaultTypeController::class, 'create'])->name('fault.type.add');
    Route::post('fault/type/add', [App\Http\Controllers\Admin\FaultTypeController::class, 'store'])->name('fault.type.add.post');
    Route::get('fault/type/edit/{faultType}', [App\Http\Controllers\Admin\FaultTypeController::class, 'edit'])->name('fault.type.edit');
    Route::put('fault/type/edit/{faultType}', [App\Http\Controllers\Admin\FaultTypeController::class, 'update'])->name('fault.type.edit.put');
    // Fault Type Routes End

    // Customer Application Routes
    Route::get('customer_applications', [App\Http\Controllers\Admin\CustomerApplicationController::class, 'index'])->name('customer_applications');
    Route::get('customer_application/add', [App\Http\Controllers\Admin\CustomerApplicationController::class, 'create'])->name('customer_application.add');
    Route::post('customer_application/add', [App\Http\Controllers\Admin\CustomerApplicationController::class, 'store'])->name('customer_application.add.post');
    Route::get('customer_application/edit/{customer_application}', [App\Http\Controllers\Admin\CustomerApplicationController::class, 'edit'])->name('customer_application.edit');
    Route::post('customer_application/edit/{customer_application}', [App\Http\Controllers\Admin\CustomerApplicationController::class, 'update'])->name('customer_application.edit.post');
    // Customer Application Routes End

    // Customer Application Routes
    Route::get('customer_application_types', [App\Http\Controllers\Admin\CustomerApplicationTypeController::class, 'index'])->name('customer_application_types');
    Route::get('customer_application_type/add', [App\Http\Controllers\Admin\CustomerApplicationTypeController::class, 'create'])->name('customer_application_type.add');
    Route::post('customer_application_type/add', [App\Http\Controllers\Admin\CustomerApplicationTypeController::class, 'store'])->name('customer_application_type.add.post');
    Route::get('customer_application_type/edit/{customer_application_type}', [App\Http\Controllers\Admin\CustomerApplicationTypeController::class, 'edit'])->name('customer_application_type.edit');
    Route::put('customer_application_type/edit/{customer_application_type}', [App\Http\Controllers\Admin\CustomerApplicationTypeController::class, 'update'])->name('customer_application_type.edit.put');
    // Customer Application Routes End

});

// Request Routes
// Get district by city id
Route::get('getDistricts/{id}', [App\Http\Controllers\CityController::class, 'districts'])->name('get.district')->where('id', '[0-9]+');

// Infrastructure Routes
Route::post('infrastructure/load', [App\Http\Controllers\InfrastructureController::class, 'load'])->name('infrastructure.load');
Route::post('infrastructure/submit', [App\Http\Controllers\InfrastructureController::class, 'submit'])->name('infrastructure.post');
// Infrastructure Routes End

// API Routes
Route::post('payment/get', [App\Http\Controllers\APIController::class, 'get_payment_list'])->name('get.payment.list');
Route::get('payment/pay', [App\Http\Controllers\APIController::class, 'pay'])->name('payment.pay');
// API Routes End

// Payment Routes
Route::post('payment/pre/auth/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'create_pre_auth'])->name('payment.pre.auth.create');
Route::post('payment/pre/auth/result/{moka_log}', [App\Http\Controllers\Admin\PaymentController::class, 'payment_pre_auth_result'])->name('payment.pre.auth.result');
Route::match(['get', 'post'], 'payment/result/{payment?}', [App\Http\Controllers\Admin\PaymentController::class, 'payment_result'])->name('payment.result');
Route::match(['get', 'post'], 'payment/auto/result', [App\Http\Controllers\Admin\PaymentController::class, 'payment_auto_result'])->name('payment.auto.result');
// Payment Routes End
// Request Routes End
