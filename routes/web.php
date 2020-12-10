<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;


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

Route::get('/', function () {
    return view('login');
});

Route::post('login_val', [LoginController::class, 'store']);
Route::get('login_val', function () {
    return view('login');
});
Route::get('product_list', [ProductController::class, 'showList']);
Route::get('product_edit/{action}', [ProductController::class, 'edit']);
Route::get('product_edit/{action}/{id}', [ProductController::class, 'edit']);
Route::post('product_edit/{action}', [ProductController::class, 'edit']);
Route::post('product_conf/{action}', [ProductController::class, 'display']);
Route::post('product_conf/{action}/{id}', [ProductController::class, 'display']);
Route::post('product_done/{action}', [ProductController::class, 'register']);
Route::post('product_done/{action}/{id}', [ProductController::class, 'register']);
