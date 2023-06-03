<?php

use App\Http\Controllers\API\ProductController;
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

Route::get('/', function () {
    return view('pages.product');
});
Route::get('/transaksi', function () {
    return view('pages.transaksi');
});


Route::get('/cms/product' , [ProductController::class, 'getAllData']);
Route::post('/cms/product/create' , [ProductController::class, 'createData']);
