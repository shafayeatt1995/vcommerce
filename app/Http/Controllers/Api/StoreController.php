<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class StoreController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        return response()->json($store);
    }

    public function create(Request $request)
    {
        $this->authorize('adminOrUser');
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
        ]);

        $image = $request->image;
        $slug = Str::slug($request->name);
        $path = 'images/store/';
        $name = $path.$slug.time().'.'.explode('/',explode(':',substr($image, 0, strpos($image,';')))[1])[1];

        if (!File::exists($path)){
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        Image::make($image)->save($name);

        $store = new Store();
        $store->user_id = Auth::id();
        $store->logo = $name;
        $store->name = $request->name;
        $store->slug = $slug.Str::random(2);
        $store->description = $request->description;
        $store->address = $request->address;
        $store->map = $request->map;
        $store->status = Auth::user()->role_id == 1 ? true:false;
        $store->save();
    }

    public function update(Request $request)
    {
        $this->authorize('adminOrSeller');
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required',
            'description' => 'required',
        ]);

        $image = $request->image;
        $slug = Str::slug($request->name);
        $path = 'images/store/';
        if(Auth::user()->store->logo !== $image){
            if (File::exists(Auth::user()->store->logo)){
                unlink(Auth::user()->store->logo);
            }
            $name = $path.$slug.time().'.'.explode('/',explode(':',substr($image, 0, strpos($image,';')))[1])[1];

            if (!File::exists($path)){
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($image)->save($name);
        }else{
            $name = Auth::user()->store->logo;
        }

        $store = Auth::user()->Store;
        $store->logo = $name;
        $store->name = $request->name;
        $store->slug = $slug.Str::random(2);
        $store->description = $request->description;
        $store->address = $request->address;
        $store->map = $request->map;
        $store->save();
    }
}
