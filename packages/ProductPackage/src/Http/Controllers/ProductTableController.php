<?php

namespace ProductPackage\Http\Controllers;

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
        return view('product-package::product-table');
    }
}