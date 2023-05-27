<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Schema;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return 'Chào mừng các bạn đã đến với PNV';
});

//Chạy controller
Route::get('/getIndex', [UserController::class, 'getIndex']);


//Tính tổng
Route::get('/sum', [App\Http\Controllers\SumController::class, 'index']);
Route::post('/sum', [App\Http\Controllers\SumController::class, 'Summ']);

//AreaOfShape
Route::get('/AreaOfShape', [App\Http\Controllers\AreaController::class, 'index']);
Route::post('/AreaOfShape', [App\Http\Controllers\AreaController::class, 'Area']);


//Singup

Route::get('signup',[App\Http\Controllers\SignupController::class ,'index']);
Route::post('signup',[App\Http\Controllers\SignupController::class ,'displayInfor']);

//
Route::post('/valida', [ValidationController::class, "validation"]);

Route::get('/addproduct',[ListProductsController::class,"showAddForm"])->name('addproduct');

Route::post('/addproduct',[ListProductsController::class,"creatSession"]);

Route::get('/showproducts',[ListProductsController::class,"showProduct"])->name('showproducts');

//sanpham
Route::get('master', [PageController::class, "getIndex"]);

//
Route::get('database',function(){
    Schema::create('loaisp',function($table){
        $table -> increments('id');
        $table -> string('ten',200);
    });
    echo "Đã thực hiện thành công";
});

Route::get('addroom',[App\Http\Controllers\addRoomsController::class ,'index']);
Route::post('addroom',[App\Http\Controllers\addRoomsController::class ,'showrooms']);


