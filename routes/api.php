<?php

use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SkuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {return $request->user();});
    Route::apiResource('products', ProductController::class);
    Route::apiResource('attributes', AttributeController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('materials', MaterialController::class);
    Route::apiResource('skus', SkuController::class);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('attributes', AttributeController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('materials', MaterialController::class)->only(['index', 'show']);
Route::apiResource('skus', SkuController::class)->only(['index', 'show']);

