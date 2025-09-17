<?php

use Illuminate\Support\Facades\Route;
use ProductPackage\Http\Controllers\ProductTableController;

/*
|--------------------------------------------------------------------------
| Web Routes for AG Grid Table View
|--------------------------------------------------------------------------
|
| This route is provided as an example. Users can choose to use this route
| or create their own routes to display the AG Grid table view.
|
*/

Route::get('/product-table', [ProductTableController::class, 'index']);