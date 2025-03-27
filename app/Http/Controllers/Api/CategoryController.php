<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Imports\CategoryImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateCheck;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rules\File;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/categories",
    *     summary="list",
    *     tags={"category"},
    *     description="list all categories",
    * @OA\Response(
    *     response=200,
    *     description="all listed categories",
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
        $category = Category::with('image')->get();

        return CategoryResource::collection($category);
    }
    
    /**
     * @OA\Get(
     *     path="/api/categories/{categories}",
     *     summary="show",
     *     tags={"category"},
     * @OA\Parameter(
     *     name="categories",
     *     in="path",
     *     description="Category ID",
     *     required=true,
     *  ),
     * @OA\Response(
     *     response=200,
     *     description="show category detail",
     * @OA\JsonContent(
     *     @OA\Property(property="title", type="string", example="Electronics"),
     *     @OA\Property(property="image_path", type="string", example="images/electronics.jpg"),
     *   )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="category not found",
     * @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="category not found"),
     *   )
     *  ) 
     * )
     */
    public function show(Category $categories)
    {   
        return new CategoryResource($categories);
    }

/**
 * @OA\Post(
 *     path="/api/categories",
 *     summary="Create a new category",
 *     tags={"category"},
 *     security={ {"bearer": {}} },
 *     @OA\RequestBody(
 *         required=true,
 *         description="Required fields for creating a category",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "image"},
 *                 @OA\Property(property="title", type="string", example="Electronic"),
 *                 @OA\Property(property="image", type="string", format="binary", description="Image file to upload")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="success")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="required field missing")
 *         )
 *     )
 * )
 */
    public function store(ValidateCheck $request)
    {
            $imagePath = $request->image->store('images');
    
            if($imagePath)
            {
                Category::create([
                    'title' => $request->title
                ])->image()->create(['path' => $imagePath]);
    
                return response()->json(['messaage'=> 'success']);
            }
        return response()->json(['messaage'=> 'fail']);
    }

/**
 * @OA\Post(
 *     path="/api/categories/{categories}",
 *     summary="Update a category",
 *     tags={"category"},
 *     security={ {"bearer": {}} },
 *     @OA\Parameter(
 *     name="categories",
 *     in="path",
 *     description="Category_id",
 *     required=true,
 *  ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Required fields for updating a category",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "image"},
 *                 @OA\Property(property="title", type="string", example="Electronic"),
 *                 @OA\Property(property="image", type="string", format="binary", description="Image file to upload")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="success")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="required field missing")
 *         )
 *     )
 * )
 */
    public function update(ValidateCheck $request, Category $categories)
    {
        $imagePath = $request->image->store('images');
        
        $categories->update(['title' => $request->title]);
        $updated = $categories->image()->update(['path' => $imagePath]);
        
        if($updated)
        {
            return response()->json(['messaage'=> 'update success']);
        }

        return response()->json(['messaage'=> 'update fail']);
    }

    /**
     * @OA\Delete(
     *      path="/api/categories/{categories}",
     *      summary="Delete a category",
     *      tags={"category"},
     *      security= { {"bearer":{}} },
     *      @OA\Parameter(
     *          name="categories",
     *          in="path",
     *          description="category_id",
     *          required=true,
     *  ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="success")
     *         )
     *  ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="fail")
     *   ),
     *  )
     * )
     */
    public function destroy(Category $categories)
    {
        $categories->image()->delete();

        if ($categories->delete()) {
            return response()->json([
                'message' => 'category deleted'
            ], 200);
        }   

        return response()->json([
            'message' => 'delete fail'
        ], 500);
    }

    /**
     * @OA\Post(
     *     path="/api/import",
     *     tags={"category"},
     *     summary="Import from Excel",
     *     security={ {"bearer":{}} },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"excel"},
     *                 @OA\Property(property="excel",type="string",format="binary",description="Excel file")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Import successful"),
     *     @OA\Response(response=400, description="Invalid file")
     * )
     */

    public function import(Request $request)
    {
        $request->validate([
            'excel' => ['required', File::types(['xlsx'])]
        ]);
        
        Excel::import(new CategoryImport, $request->excel);
        
        return response()->json(['message' => 'import sucess']);
    }
}
