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
//all protected resources
Route::middleware('auth:api')->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::post('/profile', [\App\Http\Controllers\Api\ApiController::class, 'UserDetails'])->name('details');
    Route::post('logout', [\App\Http\Controllers\Api\ApiController::class, 'logout'])->name('logout');
    Route::put('/profile/update/{id}', [\App\Http\Controllers\Api\ApiController::class, 'updateUser'])->name('update');
});
Route::middleware(['auth:api', 'scope:delete, add_product'])->group(function () {
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']);
});
Route::group(['middleware' => 'cors'], function () {
    Route::post('/register', [\App\Http\Controllers\Api\ApiController::class, 'register'])->name('register');
    Route::post('/login', [\App\Http\Controllers\Api\ApiController::class, 'login'])->name('login');
});
Route::group(['namespace' => 'Api', 'middleware' => 'api', 'prefix' => 'password'], function () {
    Route::post('create', [\App\Http\Controllers\Api\ResetPasswordController::class, 'create']);
    Route::get('/find/{token}', [\App\Http\Controllers\Api\ResetPasswordController::class, 'find']);
    Route::post('/reset', [\App\Http\Controllers\Api\ResetPasswordController::class, 'reset']);
});
