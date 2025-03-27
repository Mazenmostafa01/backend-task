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
    * @OA\Get(
    *     path="/api/products",
    *     summary="list",
    *     tags={"product"},
    *     description="list all products",
    * @OA\Response(
    *     response=200,
    *     description="all listed products",
    * @OA\JsonContent(
    *     type="array",
    * @OA\Items(
    *     type="object",
    *     @OA\Property(property="id", type="integer", example=1),
    *     @OA\Property(property="title", type="string", example="Electronics"),
    *     @OA\Property(property="image_path", type="string", example="images/electronics.jpg")
    *    )
    *   )
    *  )
    * )
    */
    public function index()
    {
        $products = Product::with('images')->get();
        
        return response()->json(['data' => ProductResource::collection($products)]);
    }

/**
 * @OA\Post(
 *     path="/api/products",
 *     summary="Create a new product with multiple images",
 *     tags={"product"},
 *     security={ {"bearer": {}} },
 *     @OA\RequestBody(
 *         required=true,
 *         description="Product details and images",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"title", "description", "price", "images[]"},
 *                 @OA\Property(property="title", type="string", example="S25"),
 *                 @OA\Property(property="description", type="string", example="RAM 12GB"),
 *                 @OA\Property(property="price", type="number", example=1099.99),
 *                 @OA\Property(property="images[]",type="array",
 *                     @OA\Items(type="string",format="binary")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product created"),
 *             @OA\Property(property="product_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
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
     * @OA\Get(
     *     path="/api/products/{product}",
     *     summary="show",
     *     tags={"product"},
     * @OA\Parameter(
     *     name="product",
     *     in="path",
     *     description="product_id",
     *     required=true,
     *  ),
     * @OA\Response(
     *     response=200,
     *     description="show product detail",
     * @OA\JsonContent(
     *     @OA\Property(property="title", type="string", example="Electronics"),
     *     @OA\Property(property="image_path", type="string", example="images/electronics.jpg"),
     *   )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="product not found",
     * @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="product not found"),
     *   )
     *  ) 
     * )
     */
    public function show(Product $product)
    {   
        return response()->json(['data' => new ProductResource($product)]);
    }

/**
 * @OA\Post(
 *     path="/api/products/{product}",
 *     summary="update a product",
 *     tags={"product"},
 *     security={ {"bearer": {}} },
 *     @OA\Parameter(
 *     name="product",
 *     in="path",
 *     description="product_id",
 *     required=true,
 *  ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Product details and images",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"title", "description", "price", "images[]"},
 *                 @OA\Property(property="title", type="string", example="S25"),
 *                 @OA\Property(property="description", type="string", example="RAM 12GB"),
 *                 @OA\Property(property="price", type="number", example=1099.99),
 *                 @OA\Property(property="images[]",type="array",
 *                     @OA\Items(type="string",format="binary")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product updated"),
 *             @OA\Property(property="product_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
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
     * @OA\Delete(
     *      path="/api/products/{product}",
     *      summary="Delete a category",
     *      tags={"product"},
     *      security= { {"bearer":{}} },
     *      @OA\Parameter(
     *          name="product",
     *          in="path",
     *          description="product_id",
     *          required=true,
     *  ),
     *     @OA\Response(
     *         response=200,
     *         description="product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="success")
     *         )
     *  ),
     *     @OA\Response(
     *         response=404,
     *         description="product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="fail")
     *   ),
     *  )
     * )
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
