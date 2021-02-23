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

Route::middleware('auth:api')->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);

    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']);

    Route::get('/profile', [\App\Http\Controllers\Api\ApiController::class, 'UserDetails'])->name('details');
    Route::post('logout', [\App\Http\Controllers\Api\ApiController::class, 'logout'])->name('logout');
    Route::put('/profile/update/{id}', [\App\Http\Controllers\Api\ApiController::class, 'updateUser'])->name('update');
});
Route::post('/register', [\App\Http\Controllers\Api\ApiController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\Api\ApiController::class, 'login'])->name('login');

Route::middleware(['auth:api', 'scope:delete, add_product'])->group(function () {
    Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/admin/logout', [\App\Http\Controllers\Api\ApiController::class, 'adminLogout'])->name('adminLogout');
});
Route::post('/admin/register', [\App\Http\Controllers\Api\ApiController::class, 'adminRegisteration'])->name('adminRegister');
Route::post('/admin/login', [\App\Http\Controllers\Api\ApiController::class, 'adminLogin'])->name('adminLogin');
