<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('expenses')->get();

        return $this->successResponse(CategoryResource::collection($categories), 'Categories retrieved successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $category = Category::create($data);

        return $this->successResponse(new CategoryResource($category->loadCount('expenses')), 'Category created successfully!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return $this->successResponse(new CategoryResource($category->loadCount('expenses')), 'Category retrieved successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return $this->successResponse(new CategoryResource($category->loadCount('expenses')), 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($category->expenses()->withTrashed()->exists()) {
            return $this->errorResponse('Category cannot be deleted!', null, 409);
        }

        $category->delete();

        return $this->successResponse(null, 'Category deleted successfully!');
    }
}
