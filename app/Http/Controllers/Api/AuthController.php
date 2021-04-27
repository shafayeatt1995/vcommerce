<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    public function user()
    {
        $user = auth('api')->user();
        $store = Store::find($user->id);
        $shippingAddresses = ShippingAddress::where('user_id', $user->id)->with('shippingCost')->latest()->get();

        return response()->json(compact('user','store','shippingAddresses'));
    }

    public function updateUser(Request $request)
    {
        $this->validate($request, [
           'name' => 'required',
           'image' => 'required',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        if($user->image !== $request->image){
            if (File::exists($user->image) && $user->image != 'images/user.png') {
                unlink($user->image);
            }
            $path = 'images/user/';
            $name = $path . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($request->image)->save($name);
            $user->image = $name;
        }
        $user->save();
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:8|max:20|confirmed',
        ]);

        $hashPassword = Auth::user()->password;
        if (Hash::check($request->old_password,$hashPassword))
        {
            if (!Hash::check($request->password,$hashPassword))
            {
                $user = Auth::user();
                $user->password = Hash::make($request->password);
                $user->save();
            }
            else
            {
                return response()->json('New Password Can not be same as Old Password', 500);
            }
        }
        else
        {
            return response()->json('Current Password not match', 500);
        }
    }
}
