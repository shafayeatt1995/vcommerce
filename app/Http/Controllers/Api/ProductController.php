<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\FeatureDescription;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Specification;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('adminOrSeller');
        if(isset(Auth::user()->store->id)){
            $products = Product::where('store_id',Auth::user()->store->id)->with('user','brand','category','subCategory')->latest()->paginate(20);
            return response()->json($products);
        }else{
            return response()->json('We Can not find your store ',404);
        }
    }

    public function create()
    {
        $this->authorize('adminOrSeller');
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('brands','categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('adminOrSeller');
        $this->validate($request, [
            'name' => 'required|max:255',
            'model' => 'required|max:50',
            'category' => 'required|numeric',
            'subCategory' => 'required|numeric',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        $image = $request->image;
        $slug = Str::slug($request->name);
        $path = 'images/product/feature/';
        $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        Image::make($image)->save($name);

        $productImages = ProductImage::where('store_id',Auth::user()->store->id)->where('product_id', null)->get();


        $product = new Product();
        $product->user_id = Auth::id();
        $product->store_id = Auth::user()->store->id;
        if ($request->brandName == '' || $request->brandName == null) {
            $product->brand_id = $request->brand;
            $product->brand_name = null;
        } else {
            $product->brand_id = null;
            $product->brand_name = $request->brandName;
        }
        $product->category_id = $request->category;
        $product->sub_category_id = $request->subCategory;
        $product->feature_image = $name;
        $product->name = $request->name;
        $product->slug = $slug . Str::random(2);
        $product->description = $request->description;
        $product->model_name = $request->model;
        $product->status = $request->visible=='true'? true:false;
        $product->price = $request->price;
        if ($request->discount !== '' || $request->discount !== null){
            $product->discount_fixed = $request->discount;
            $product->discount_percent = (($request->discount * 100)/$request->price);
        } else{
            $product->discount_fixed = null;
            $product->discount_percent = null;
        }
        $product->stock_status = $request->stock=='true'? true:false;
        $product->save();

        foreach ($productImages as $data){
            $data->product_id = $product->id;
            $data->save();
        }

        foreach ($request->colors as $data){
            $color = new Color();
            $color->product_id = $product->id;
            $color->name = $data['name'];
            $color->color = $data['value'];
            $color->save();
        }

        foreach ($request->variants as $data){
            $variant = new Variant();
            $variant->product_id = $product->id;
            $variant->variant = $data['value'];
            $variant->save();
        }

        foreach ($request->descriptions as $data){
            $description = new FeatureDescription();
            $description->product_id = $product->id;
            $description->feature_description = $data['value'];
            $description->save();
        }

        foreach ($request->specification as $data){
            $specification = new Specification();
            $specification->product_id = $product->id;
            $specification->title = $data['title'];
            $specification->description = $data['description'];
            $specification->save();
        }

        foreach ($request->tags as $data){
            $tag = new Tag();
            $tag->product_id = $product->id;
            $tag->tag = $data['value'];
            $tag->save();
        }
    }

    public function edit(Product $product)
    {
        $this->authorize('adminOrSeller');
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $subCategories = SubCategory::where('category_id',$product->category_id)->orderBy('name')->get();
        $colors = Color::where('product_id', $product->id)->get(['color', 'name']);
        $variants = Variant::where('product_id', $product->id)->get('variant');
        $descriptions = FeatureDescription::where('product_id', $product->id)->get('feature_description');
        $specifications = Specification::where('product_id', $product->id)->get(['title','description']);
        $tags = Tag::where('product_id', $product->id)->get('tag');
        return response()->json(compact('product','brands','categories','subCategories','colors','variants','descriptions','specifications','tags'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('adminOrSeller');
        $this->validate($request, [
            'name' => 'required|max:255',
            'model' => 'required|max:50',
            'category' => 'required|numeric',
            'subCategory' => 'required|numeric',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        $image = Str::substr($request->image, 1);
        $slug = Str::slug($request->name);
        if ($image !== $product->feature_image) {
            $path = 'images/product/feature/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            if (File::exists($product->feature_image)) {
                unlink($product->feature_image);
            }
            Image::make($request->image)->save($name);
        } else {
            $name = $product->feature_image;
        }


        if ($request->brandName == '' || $request->brandName == null) {
            $product->brand_id = $request->brand;
            $product->brand_name = null;
        } else {
            $product->brand_id = null;
            $product->brand_name = $request->brandName;
        }
        if ($product->name !== $request->name) {
            $product->slug = $slug . Str::random(2);
        }
        $product->category_id = $request->category;
        $product->sub_category_id = $request->subCategory;
        $product->feature_image = $name;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->model_name = $request->model;
        $product->status = $request->visible=='true'? true:false;
        $product->price = $request->price;
        if ($request->discount !== '' || $request->discount !== null){
            $product->discount_fixed = $request->discount;
            $product->discount_percent = (($request->discount * 100)/$request->price);
        } else{
            $product->discount_fixed = null;
            $product->discount_percent = null;
        }
        $product->stock_status = $request->stock=='true'? true:false;
        $product->save();

        foreach ($product->colors as $data){
            $data->delete();
        }
        foreach ($request->colors as $data){
            $color = new Color();
            $color->product_id = $product->id;
            $color->name = $data['name'];
            $color->color = $data['color'];
            $color->save();
        }

        foreach ($product->variants as $data){
            $data->delete();
        }
        foreach ($request->variants as $data){
            $variant = new Variant();
            $variant->product_id = $product->id;
            $variant->variant = $data['variant'];
            $variant->save();
        }

        foreach ($product->featureDescriptions as $data){
            $data->delete();
        }
        foreach ($request->descriptions as $data){
            $description = new FeatureDescription();
            $description->product_id = $product->id;
            $description->feature_description = $data['feature_description'];
            $description->save();
        }

        foreach ($product->specifications as $data){
            $data->delete();
        }
        foreach ($request->specification as $data){
            $specification = new Specification();
            $specification->product_id = $product->id;
            $specification->title = $data['title'];
            $specification->description = $data['description'];
            $specification->save();
        }

        foreach ($product->tags as $data){
            $data->delete();
        }
        foreach ($request->tags as $data){
            $tag = new Tag();
            $tag->product_id = $product->id;
            $tag->tag = $data['tag'];
            $tag->save();
        }
    }

    public function destroy(Product $product)
    {
        $this->authorize('adminOrSeller');
        $productImages = ProductImage::where('product_id', $product->id)->get();
        foreach ($productImages as $data){
            if (File::exists($data->image)) {
                unlink($data->image);
            }
        }
        if (File::exists($product->feature_image)) {
            unlink($product->feature_image);
        }
        $product->delete();
    }

    public function getSubCategory($id)
    {
        $this->authorize('adminOrSeller');
        $subCategories = SubCategory::where('category_id', $id)->get();
        return response()->json($subCategories);
    }

    public function productImage(Request $request)
    {
        $this->authorize('adminOrSeller');

        $images = ProductImage::where('store_id', Auth::user()->store->id)->where('product_id', null)->count();
        if ($images <= 14) {
            $image = $request->file('file');
            $path = 'images/product/images/';
            $imageName = time() . uniqid() . '.' . $image->extension();
            $image->move($path, $imageName);

            $productImage = new ProductImage();
            $productImage->product_id = null;
            $productImage->image = $path . $imageName;
            $productImage->store_id = Auth::user()->store->id;
            $productImage->save();
        } else {
            return response()->json('You Can not Upload More Than 15 Images', 500);
        }
    }

    public function editProductImage(Request $request)
    {
        $this->authorize('adminOrSeller');

        $images = ProductImage::where('product_id', $request->id)->count();
        if ($images <= 14) {
            $image = $request->file('file');
            $path = 'images/product/images/';
            $imageName = time() . uniqid() . '.' . $image->extension();
            $image->move($path, $imageName);

            $productImage = new ProductImage();
            $productImage->product_id = $request->id;
            $productImage->image = $path . $imageName;
            $productImage->store_id = Auth::user()->store->id;
            $productImage->save();
        } else {
            return response()->json('You Can not Upload More Than 15 Images', 500);
        }
    }

    public function getImage()
    {
        $this->authorize('adminOrSeller');
        $images = ProductImage::where('store_id', Auth::user()->store->id)->where('product_id', null)->get();
        return response()->json($images);
    }

    public function getEditImage($id)
    {
        $this->authorize('adminOrSeller');
        $images = ProductImage::where('product_id', $id)->get();
        return response()->json($images);
    }

    public function productImageDelete($id)
    {
        $this->authorize('adminOrSeller');
        $image = ProductImage::where('id', $id)->first();
        if (File::exists($image->image)) {
            unlink($image->image);
        }
        $image->delete();
    }

    public function getProduct($slug)
    {
        $product = Product::active()->status()->where('slug', $slug)->with('brand', 'category', 'subCategory', 'productImages', 'featureDescriptions', 'specifications', 'colors', 'variants', 'user', 'orders')->withCount('orders','reviews')->first();
        if(isset($product)){
            $products = Product::active()->status()->where('sub_category_id', $product->sub_category_id)->with('brand', 'category', 'subCategory', 'productImages', 'featureDescriptions', 'specifications', 'colors', 'variants', 'user', 'orders')->withCount('reviews')->inRandomOrder()->take(10)->get();
            return response()->json(compact('product','products'));
        } else {
            return response()->json('product Not Found', 404);
        }
    }

    public function viewCount(Product $product)
    {
        $product->increment('view_count');
    }
}
