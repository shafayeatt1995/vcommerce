<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $subCategories = SubCategory::with('category')->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('subCategories','categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required|numeric',
        ]);

        $subCategory = new SubCategory();
        $subCategory->category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->save();
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required|numeric',
        ]);

        $subCategory->category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->save();
    }

    public function destroy(SubCategory $subCategory)
    {
        $this->authorize('admin');
        $subCategory->delete();
    }
}
