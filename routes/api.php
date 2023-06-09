<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// create api
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// create api
Route::get('/get-product', [App\Http\Controllers\APiController::class, 'getProducts']);
Route::get('/get-product/{id}', [App\Http\Controllers\APiController::class, 'getOneProduct']);
Route::post('/add-product', [App\Http\Controllers\APiController::class, 'addProduct']);
Route::delete('/delete-product/{id}', [App\Http\Controllers\APiController::class, 'deleteProduct']);
Route::put('/edit-product/{id}', [App\Http\Controllers\APiController::class, 'editProduct']);

Route::post('/upload-image', [App\Http\Controllers\APiController::class, 'uploadImage']);

// API Tiki
Route::get('/get-productmid', [App\Http\Controllers\ProducttikiController::class, 'getProducts']);
Route::post('/add-productmid', [App\Http\Controllers\ProducttikiController::class, 'addProduct']);
Route::post('/upload-imagemid', [App\Http\Controllers\ProducttikiController::class, 'uploadImage']);
