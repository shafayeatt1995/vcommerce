<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingCost;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $cost = ShippingCost::latest()->paginate(20);
        return response()->json($cost);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'town' => 'required',
            'zip' => 'required|unique:shipping_costs',
        ]);

        $shippingCost = new ShippingCost();
        $shippingCost->town = $request->town;
        $shippingCost->zip = $request->zip;
        $shippingCost->express_price = $request->express_price;
        $shippingCost->normal_price = $request->normal_price;
        $shippingCost->save();

        $cost = ShippingCost::latest()->paginate(20);
        return response()->json($cost);
    }

    public function update(Request $request, ShippingCost $shippingCost)
    {
        $this->authorize('admin');
        $this->validate($request, [
            'town' => 'required',
            'zip' => 'required',
        ]);

        $shippingCost->town = $request->town;
        $shippingCost->zip = $request->zip;
        $shippingCost->express_price = $request->express_price;
        $shippingCost->normal_price = $request->normal_price;
        $shippingCost->save();

        $cost = ShippingCost::latest()->paginate(20);
        return response()->json($cost);
    }

    public function destroy(ShippingCost $shippingCost)
    {
        $shippingCost->delete();

        $cost = ShippingCost::latest()->paginate(20);
        return response()->json($cost);
    }

    public function getShippingCost()
    {
        $shippingCostes = ShippingCost::orderBy('town')->get();
        return response()->json($shippingCostes);
    }
}
