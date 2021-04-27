<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $categories = Category::with('subCategories')->latest()->paginate(20);
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'name' => 'required|unique:categories',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
    }

    public function destroy(Category $category)
    {
        $this->authorize('admin');
        $category->delete();
    }

    public function allCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
