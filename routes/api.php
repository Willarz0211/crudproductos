<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;

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

Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/restore/{id}', [ProductController::class, 'restore']);
Route::post('/storeProduct', [ProductController::class, 'store']);
Route::put('/updateProduct/{id}', [ProductController::class, 'update']);
Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy']);
