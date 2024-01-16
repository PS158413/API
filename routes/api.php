<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Kuin\KuinController;
use App\Http\Controllers\Kuin\ProductsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

/**
 * Authentication
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', [AuthController::class, 'user']);
});

/**
 * User
 */
Route::get('user/all', [UserController::class, 'UserAll']);
Route::resource('user', UserController::class);

/**
 * Product
 */
Route::group(['prefix' => 'product'], function () {
    Route::get('', [ProductController::class, 'index_product']);
    Route::get('/all', [ProductController::class, 'indexAll']);
    Route::get('/{id}', [ProductController::class, 'show_product']);
    Route::post('', [ProductController::class, 'store_product']);
    Route::put('/{id}', [ProductController::class, 'update_product']);
    Route::delete('/{id}', [ProductController::class, 'destroy_product']);
    Route::get('/code/{code}', [CheckoutController::class, 'show_product_by_code']);
    Route::post('/barcode/{barcode}', [CheckoutController::class, 'post_product_by_barcode']);
    Route::get('/checkoutproduct/all', [CheckoutController::class, 'checkoutProduct_All']);
    Route::post('/checkoutproduct', [CheckoutController::class, 'post_checkoutproduct']);
    Route::delete('/checkoutproduct/{id}', [CheckoutController::class, 'delete_checkoutproduct_by_id']);
    Route::delete('/checkoutproduct', [CheckoutController::class, 'delete_all_checkoutproducts']);
    Route::post('/receipt', [CheckoutController::class, 'post_receipt']);
    // delete all products in checkout
    Route::delete('/checkoutproduct/all', [CheckoutController::class, 'delete_all_checkoutproducts']);
    Route::get('/code/{code}', [CheckoutController::class, 'show_product_by_code']);
    Route::post('/barcode/{barcode}', [CheckoutController::class, 'post_product_by_barcode']);
    // Route::get('/checkoutproduct/view/all', [CheckoutController::class, 'checkoutProducts_All']);
    Route::post('/checkoutproduct', [CheckoutController::class, 'post_checkoutproduct']);
    Route::delete('/checkoutproduct/{id}', [CheckoutController::class, 'delete_checkoutproduct_by_id']);
    Route::delete('/checkoutproduct', [CheckoutController::class, 'delete_all_checkoutproducts']);
    Route::post('/receipt', [CheckoutController::class, 'post_receipt']);
    // delete all products in checkout
    Route::delete('/checkoutproduct/all', [CheckoutController::class, 'delete_all_checkoutproducts']);
});

Route::get('receipts', [CheckoutController::class, 'get_all_receipts']);
// getUserByCustomernumber
Route::get('user/customernumber/{customernumber}', [CheckoutController::class, 'getUserByCustomernumber']);
// get all customers when name is customernumber
Route::get('user/customernumber/all', [CheckoutController::class, 'getAllCustomers']);
/**
 * Category
 */
Route::group(['prefix' => 'category'], function () {
    Route::get('', [CategoryController::class, 'index_Category']);
    Route::get('/all', [CategoryController::class, 'categoryAll']);
    Route::get('/{id}', [CategoryController::class, 'show_category']);
    Route::post('', [CategoryController::class, 'store_category']);
    Route::put('/{id}', [CategoryController::class, 'update_category']);
    Route::delete('/{id}', [CategoryController::class, 'destroy_category']);
    Route::delete('/detach/{id}', [CategoryController::class, 'detach_product']);
});

/**
 * Order
 */
Route::group(['prefix' => 'order'], function () {
    Route::get('', [OrderController::class, 'index_order']);
    Route::post('', [OrderController::class, 'store_order']);
    Route::get('/all', [OrderController::class, 'OrderAll']);
    Route::get('/{id}', [OrderController::class, 'show_order']);
});

/**
 * Order Customer
 */
Route::group(['prefix' => 'customer'], function () {
    Route::post('', [CustomerController::class, 'store_customer_order']);
});

/**
 * Role
 */
Route::group(['prefix' => 'role'], function () {
    Route::get('', [RoleController::class, 'index_role']);
    Route::get('/all', [RoleController::class, 'roleAll']);
    Route::get('/{id}', [RoleController::class, 'show_role']);
    Route::post('', [RoleController::class, 'store_role']);
    Route::put('/{id}', [RoleController::class, 'update_role']);
    Route::delete('/{id}', [RoleController::class, 'destroy_role']);
    Route::put('user/{id}', [RoleController::class, 'update_role_user']);
});

/**
 * Availability
 */
Route::group(['prefix' => 'availability'], function () {
    Route::get('', [AvailabilityController::class, 'index_availability']);
    Route::get('/all', [AvailabilityController::class, 'availabilityAll']);
    Route::get('/{id}', [AvailabilityController::class, 'show_availability']);
    Route::post('', [AvailabilityController::class, 'store_availability']);
    Route::put('/{id}', [AvailabilityController::class, 'update_availability']);
    Route::delete('/{id}', [AvailabilityController::class, 'destroy_availability']);
});

///////////////////////////////////////////////////////////////////

/**
 * Kuin API
 */

/**
 * Products
 */
Route::group(['prefix' => 'kuin'], function () {
    Route::get('products', [ProductsController::class, 'index_kuin']);
});

// KuinController routes
//route to admin.index in KuinController
Route::group(['prefix' => 'kuin'], function () {
    Route::get('', [KuinController::class, 'index']);
    Route::post('/store', [KuinController::class, 'store']);
    Route::get('/orders', [KuinController::class, 'getOrder']);
    Route::get('/order/{id}', [KuinController::class, 'show']);
});
