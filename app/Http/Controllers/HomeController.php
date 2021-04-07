<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $currency = Currency::where('default', true)->first();
        return view('welcome', compact('currency'));
    }
}
