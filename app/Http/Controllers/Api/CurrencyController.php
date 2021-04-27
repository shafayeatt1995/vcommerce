<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CurrencyController extends Controller
{
    public function index()
    {
        $currency = Currency::where('default', true)->first();
        $currencies = Currency::orderBy('country')->get();
        return response()->json(compact('currency', 'currencies'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
           'name' => 'required',
           'icon' => 'required',
           'rate' => 'required | numeric' ,
        ]);

        $currency = new Currency();
        $currency->icon = $request->icon;
        $currency->country = $request->country;
        $currency->name = $request->name;
        $currency->rate = $request->rate;
        $currency->default = Currency::count() > 0 ? false:true;
        $currency->save();
    }

    public function update(Currency $currency, Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'name' => 'required',
            'icon' => 'required',
            'rate' => 'required | numeric' ,
        ]);

        $currency->icon = $request->icon;
        $currency->country = $request->country;
        $currency->name = $request->name;
        $currency->rate = $request->rate;
        $currency->save();
    }

    public function destroy(Currency $currency)
    {
        $this->authorize('admin');
        $currency->delete();
    }

    public function setDefault(Currency $currency)
    {
        $this->authorize('admin');
        $oldDefault = Currency::where('default', true)->get();
        foreach ($oldDefault as $data){
            $data->default = false;
            $data->save();
        }
        $currency->default = true;
        $currency->save();
        return response()->json($currency->name . ' is Now default Currency', 200);
    }
}
