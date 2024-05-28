<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CrafterController;
use App\Http\Controllers\Api\CustomizationController;
use App\Http\Controllers\Api\GouvController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SkuController;
use App\Http\Controllers\Api\TaxController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->group(static function () {
    Route::get('/user', static fn(Request $request) => $request->user());
    Route::apiResource('products', ProductController::class);
    Route::apiResource('attributes', AttributeController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('materials', MaterialController::class);
    Route::apiResource('skus', SkuController::class);
    Route::apiResource('taxes', TaxController::class);
    Route::apiResource('customizations', CustomizationController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('crafters', CrafterController::class);
    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('images', ImageController::class);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('attributes', AttributeController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('materials', MaterialController::class)->only(['index', 'show']);
Route::apiResource('skus', SkuController::class)->only(['index', 'show']);
Route::apiResource('crafters', CrafterController::class)->only(['index', 'show']);

Route::post('gouv', [GouvController::class, 'show']);

