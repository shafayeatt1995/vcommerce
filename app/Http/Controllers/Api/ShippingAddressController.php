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
        $this->authorize('authCheck');
        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $this->authorize('authCheck');
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
        $address->default = Auth::user()->shippingAddresses->count() > 0 ? false:true;
        $address->save();

        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }

    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        $this->authorize('authCheck');
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
        $this->authorize('authCheck');
        $shippingAddress->delete();

        $addresses = ShippingAddress::where('user_id', Auth::id())->with('shippingCost')->get();
        return response()->json($addresses);
    }

    public function setDefault(ShippingAddress $shippingAddress)
    {
        $this->authorize('authCheck');
        $oldDefault = ShippingAddress::where('default', true)->get();
        foreach ($oldDefault as $data){
            $data->default = false;
            $data->save();
        }
        $shippingAddress->default = true;
        $shippingAddress->save();

    }
}
