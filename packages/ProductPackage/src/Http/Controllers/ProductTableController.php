<?php

namespace ProductPackage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductTableController extends Controller
{
    /**
     * Display the product table view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // This view contains a flexible AG Grid implementation
        // Users should customize the column definitions and API endpoint
        // to match their data structure
        return view('product-package::product-table');
    }
}