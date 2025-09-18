<?php

use Illuminate\Support\Facades\Route;
use ProductPackage\Http\Controllers\ProductTableController;

Route::get('/product-table', [ProductTableController::class, 'index']);