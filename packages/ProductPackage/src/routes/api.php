<?php

use Illuminate\Support\Facades\Route;
use ProductPackage\Http\Controllers\API\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes for Product Management
|--------------------------------------------------------------------------
|
| These routes are provided for convenience and only work if you use the 
| provided migrations and models. If you have your own database structure,
| you should create your own API routes.
|
*/

Route::prefix('api')->group(function () {
    Route::apiResource('products', ProductController::class);
});