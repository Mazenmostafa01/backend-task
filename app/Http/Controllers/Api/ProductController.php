<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductCheck;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('images')->get();
        
        return response()->json(['data' => ProductResource::collection($products)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCheck $request)
    {
        $products = Product::create([
            'category_id'=> 1,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price
            ]);
    
            $imagePaths = [];
            foreach ($request->file('images') as $images) {
                $imagePaths[] = $images->store('images');
            }
    
            foreach($imagePaths as $path){
                $products->images()->create(['path' => $path]);
            }
    
            return response()->json(['data' => 'success']);            
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {   
        return response()->json(['data' => new ProductResource($product)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCheck $request, Product $product)
    {
        $product->update([
        'category_id'=> 1,
        'title' => $request->title,
        'description' => $request->description,
        'price' => $request->price
         ]);
    
         $productCountImages = $product->images()->count();
         $imagePaths = [];

         if($productCountImages >= 1)
         {
             $product->images()->delete();
             
             foreach ($request->file('images') as $images) {
                 $imagePaths[] = $images->store('images');
                }
                
                foreach($imagePaths as $path){
                    $product->images()->create(['path' => $path]);
                }
            }
            return response()->json(['message' => 'success']);
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
            $product->images()->delete();
    
            $product->delete();
    
            return response()->json([
                'message' => 'product deleted'
            ]);
    }
}
