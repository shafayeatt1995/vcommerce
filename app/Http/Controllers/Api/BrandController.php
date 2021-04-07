<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
	public function index()
	{
		$this->authorize('admin');
		return Brand::latest()->paginate(20);
	}

	public function store(Request $request)
	{
		$this->authorize('admin');
		$this->validate($request, [
			'name' => 'required|unique:brands',
			'image' => 'required',
		]);

		$image = $request->image;
		$slug = Str::slug($request->name);
		$path = 'images/brand/';
		$name = $path.$slug.time().'.'.explode('/',explode(':',substr($image, 0, strpos($image,';')))[1])[1];

		if (!File::exists($path)){
			File::makeDirectory($path, $mode = 0777, true, true);
		}

		Image::make($image)->save($name);


		$brand = new Brand();
		$brand->name = $request->name;
		$brand->slug = $slug;
		$brand->image = $name;
		$brand->save();
	}

	public function update(Request $request, Brand $brand)
	{
		$this->authorize('admin');
		$this->validate($request, [
			'name' => 'required',
			'image' => 'required',
		]);

		$image = $request->image;
		$slug = Str::slug($request->name);
		if ($image == $brand->image){
			$name = $brand->image;
		} else {
			if (File::exists($brand->image)) {
				unlink($brand->image);
			}
			$path = 'images/brand/';
			$name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];

			if (!File::exists($path)) {
				File::makeDirectory($path, $mode = 0777, true, true);
			}

			Image::make($image)->save($name);
		}

		$brand->name = $request->name;
		$brand->slug = $slug;
		$brand->image = $name;
		$brand->save();
	}

	public function destroy(Brand $brand)
	{
		$this->authorize('admin');
		if (File::exists($brand->image)) {
			unlink($brand->image);
		}
		$brand->delete();
	}
}
