<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    public function index()
    {
        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'shipping_cost_id'=>'required|integer',
            'address_name'=>'required',
            'name'=>'required',
            'address'=>'required',
            'phone'=>'required'
        ]);

        $address = new ShippingAddress();
        $address->user_id = Auth::id();
        $address->shipping_cost_id = $request->shipping_cost_id;
        $address->address_name = $request->address_name;
        $address->name = $request->name;
        $address->address = $request->address;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->save();

        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }

    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        $this->validate($request,[
            'shipping_cost_id'=>'required|integer',
            'address_name'=>'required',
            'name'=>'required',
            'address'=>'required',
            'phone'=>'required'
        ]);

        $shippingAddress->shipping_cost_id = $request->shipping_cost_id;
        $shippingAddress->address_name = $request->address_name;
        $shippingAddress->name = $request->name;
        $shippingAddress->address = $request->address;
        $shippingAddress->phone = $request->phone;
        $shippingAddress->email = $request->email;
        $shippingAddress->save();

        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }

    public function destroy(ShippingAddress $shippingAddress)
    {
        $shippingAddress->delete();

        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }
}
