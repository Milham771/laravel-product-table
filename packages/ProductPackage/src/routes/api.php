<?php

use Illuminate\Support\Facades\Route;
use ProductPackage\Http\Controllers\API\ProductController;

Route::apiResource('products', ProductController::class);