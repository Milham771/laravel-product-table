<?php

use Illuminate\Support\Facades\Route;
use ProductPackage\Http\Controllers\API\ProductController;

Route::middleware(['product-package.cors'])->group(function () {
    Route::apiResource('products', ProductController::class);
});