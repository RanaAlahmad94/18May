<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\reviews\ReviewController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\users\UserController;
use App\Http\Controllers\vendors\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout']);;
Route::post('/login',[AuthController::class,'login'])->name('login');


Route::group([

  //'prefix' => 'products',
  //  'middleware' => ['auth:sanctum','throttle:60,1']
    'middleware' => ['auth:sanctum']
],function (){
    Route::match(['put', 'patch'], '/update-user/{id}',[UserController::class,'updateRoles']);
    Route::group([
        'middleware' => 'isadmin'
    ],function(){
        Route::post('/add-product',[ProductController::class,'store']);
        Route::match(['put', 'patch'], '/update-product/{id}',[ProductController::class,'update']);
        Route::delete( '/delete-product/{id}',[ProductController::class,'destroy']);
        Route::get('/u',[UserController::class,'getUsersByRole']);

        Route::post('/add-vendor',[VendorController::class,'store']);
        Route::get('/all-vendors',[VendorController::class,'index']);

    });

    Route::get('/all-products',[ProductController::class,'index']);
    Route::get('/product/{letter}',[ProductController::class,'filterProductsByCategory']);
    Route::get('/product/byId/{id}',[ProductController::class,'show']);
    Route::get('/all-users',[UserController::class,'index']);

    Route::post('/add-review',[ReviewController::class,'store']);
    Route::get('/all-reviews',[ReviewController::class,'index']);
    Route::get('/review/{userId}',[ReviewController::class,'filterReviewsByUser']);
    Route::get('/reviewsFrequency',[ReviewController::class,'usersReviewsFrequency']);
    Route::delete('/delete-review/{review}', [ReviewController::class, 'destroy']);

});


