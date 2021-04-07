<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return response()->json($wishlist);
    }

    public function create($id)
    {
        $exist = Wishlist::where('user_id',Auth::id())->where('product_id',$id)->first();

        if(!isset($exist)){
            $wishlist = new Wishlist();
            $wishlist->user_id = Auth::id();
            $wishlist->product_id = $id;
            $wishlist->save();
            return response()->json('Product Successfully Add To Wishlist');
        }else{
            $exist->delete();
            return response()->json('Product Successfully Remove To Wishlist');
        }

    }

    public function delete(Wishlist $wishlist)
    {
        $wishlist->delete();
        return response()->json('Product Successfully Remove To Wishlist');
    }
}
