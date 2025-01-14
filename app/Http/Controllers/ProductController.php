<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index')->with('productsView', $products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $preparProduct = [
            'name' => $request->name,
            'price' => $request->price,
            'user_id' => Auth::id(),
        ];
        $product = Product::create($preparProduct);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit')->with('product', $product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $preparProduct = [
            'name' => $request->name,
            'price' => $request->price,
            'user_id' => Auth::id(),
        ];
        $productInst = Product::find($product->id);
        $productInst ->update($preparProduct);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $productInst = Product::find($product->id);
        $productInst->delete();

        return redirect()->route('products.index');
    }
}
