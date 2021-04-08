<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CouponController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $coupons = Coupon::latest()->with('user')->get();
        return response()->json($coupons);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request,[
            'name' => 'required',
            'coupon' => 'required|unique:coupons',
            'image' => 'required',
            'percent_offer' => 'max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if (isset($request->image)) {
            $image = $request->image;
            $slug = Str::slug($request->name);
            $path = 'images/coupon/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image)->save($name);
        }

        $coupon = new Coupon();
        $coupon->user_id = Auth::id();
        $coupon->name = $request->name;
        $coupon->coupon = $request->coupon;
        $coupon->usages = $request->usages;
        $coupon->image = $name;
        $coupon->target_price = $request->target_price == null ? '0':$request->target_price;
        $coupon->fixed_offer = $request->fixed_offer;
        $coupon->percent_offer = $request->percent_offer;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();

        $coupons = Coupon::latest()->get();
        return response()->json($coupons);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->authorize('admin');
        $this->validate($request,[
            'name' => 'required',
            'coupon' => 'required',
            'image' => 'required',
            'percent_offer' => 'max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($request->image !== $coupon->image) {
            if (File::exists($coupon->image)) {
                unlink($coupon->image);
            }
            $image = $request->image;
            $slug = Str::slug($request->name);
            $path = 'images/coupon/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image)->save($name);
        } else {
            $name = $coupon->image;
        }

        $coupon->user_id = Auth::id();
        $coupon->name = $request->name;
        $coupon->coupon = $request->coupon;
        $coupon->usages = $request->usages;
        $coupon->image = $name;
        $coupon->target_price = $request->target_price == null ? '0':$request->target_price;
        $coupon->fixed_offer = $request->fixed_offer;
        $coupon->percent_offer = $request->percent_offer;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();

        $coupons = Coupon::latest()->get();
        return response()->json($coupons);
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('admin');
        if (File::exists($coupon->image)) {
            unlink($coupon->image);
        }
        $coupon->delete();

        $coupons = Coupon::latest()->get();
        return response()->json($coupons);
    }

    public function coupon(Request $request)
    {
        $this->authorize('authCheck');
        $today = Carbon::now()->toDateString();
        $find = Coupon::where('coupon', $request->code)->whereDate('start_date', '<=', $today)->whereDate
        ('end_date', '>=', $today)->first();
        if(isset($find)) {
            if ($find->usages === null) {
                $coupon = $find;
            } else {
                $exist = Auth::user()->orders->where('coupon_id', $find->id)->count();
                $coupon = $exist < $find->usages ? $find:['message'=>'You Can Use This Coupon Maximum '.$find->usages.' Time'];
            }
            return response()->json($coupon);
        } else{
            return response()->json(['message'=>'Please use a valid coupon'], 422);
        }
    }
}
