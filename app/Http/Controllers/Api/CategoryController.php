<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Attachment;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::with('image')->get();

        return CategoryResource::collection($category);
    }
    
    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Category $categories)
    {   
        return new CategoryResource($categories);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Category $categories)
    {
        $categories->image()->delete();
        
        $categories->delete();
        
        return response()->json([
            'message' => 'category deleted'
        ]);
        return response()->json(['messaage'=> 'delete fail']);
    }
}
