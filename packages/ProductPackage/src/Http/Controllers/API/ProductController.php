<?php

namespace ProductPackage\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ProductPackage\Models\Product;

/**
 * Product API Controller
 * 
 * This controller provides optional API endpoints for managing products.
 * These endpoints only work if you use the provided migrations and models.
 * 
 * If you have your own database structure, you should create your own API endpoints.
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create($validatedData);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \ProductPackage\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \ProductPackage\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($validatedData);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ProductPackage\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}