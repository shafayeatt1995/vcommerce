<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use function Sodium\increment;

class CartController extends Controller
{
    public function index()
    {
        $this->authorize('authCheck');
        return $carts = Cart::where('user_id', Auth::id())->with('product', 'color', 'variant')->get();
    }

    public function create(Request $request)
    {
        $this->authorize('authCheck');
        $exist = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();
        if(!isset($exist)){
            $cart = new Cart();
            $cart->user_id = Auth::id();
            $cart->product_id = $request->product_id;
            $cart->variant_id = $request->variant;
            $cart->color_id = $request->color;
            $cart->quantity = isset($request->quantity) ? $request->quantity:'1';
            $cart->save();
            return response()->json('Product Successfully Added To Cart',200);
        }else{
            $exist->delete();
            return response()->json('Product Successfully Remove To Cart',200);
        }
    }

    public function update(Request $request)
    {
        $this->authorize('authCheck');
        $cart = $request->all();
        foreach ($cart as $data){
            $newCart = Cart::find($data['id']);
            $newCart->quantity = $data['quantity'];
            $newCart->save();
        }
        return response()->json('Cart Successfully Updated',200);
    }

    public function delete(Cart $cart)
    {
        $this->authorize('authCheck');
        $cart->delete();
        return response()->json('Product Successfully Remove',200);
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

    public function check()
    {
        $this->authorize('authCheck');
        $cartProducts = Cart::where('user_id', Auth::id())->get();
        $products = Product::where('user_id', Auth::id())->get();
        return response()->json(compact('cartProducts','products'));
    }
}
