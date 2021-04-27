<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function socialLogin($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function socialRedirect($provider)
    {
        $user = Socialite::driver($provider)->user();

        $existUser = User::where('email', $user->email)->first();
        $password = 'vcommerce'.$user->email;
        $credentials = ['email' => $user->email, 'password' => $password];
        if (!isset($existUser)){
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = bcrypt($password);
            $newUser->social_account = true;
            $newUser->save();

            if (Auth::attempt($credentials)) {
                return redirect('/login');
            } else {
                return redirect('/');
            }
        } else {
            if (Hash::check($password, $existUser->password)) {
                if (Auth::attempt($credentials)) {
                    return redirect('/login');
                } else {
                    return redirect('/');
                }
            } else {
                toast('This email already have a account','error');
                return redirect('/login');
            }
        }
    }
}
