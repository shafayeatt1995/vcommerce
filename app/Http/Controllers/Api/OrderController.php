<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Token;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('authCheck');
        $orders = Order::where('user_id', Auth::id())->with('products')->latest()->paginate(20);
        return response()->json($orders);
    }

    public function create(Request $request)
    {
        $this->authorize('authCheck');
        $carts = Cart::where('user_id', Auth::id())->with('product', 'color', 'variant')->get();

        $price = 0;
        foreach ($carts as $key => $value) {
            $price += $value->product->price * $value->quantity;
        };

        $discount = 0;
        foreach ($carts as $key => $value) {
            $discount += $value->product->discount_fixed;
        };

        $shipping = $request->selectAddress['shipping_cost'];
        $expMonth = Str::substr($request->expire, 5);
        $expYear = Str::substr($request->expire, 0, 4);

        $totalPrice = $price + ($request->shippingType === true ? $shipping['normal_price'] : $shipping['express_price']) - ($request->couponUse === true ? $request->cartCoupon['fixed_offer'] !== null ? $request->cartCoupon['fixed_offer'] : ($price * $request->cartCoupon['percent_offer']) / 100 : $discount);

        $billingAddress = array('name' => $request->name, 'address' => $request->address, 'phone' => $request->phone, 'email' => $request->email);
        $deliveryStatus = array('processing' => 'running', 'warehouse' => 'pending', 'onTheWay' => 'pending', 'delivery' => 'pending');

        if ($request->paymentType === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $token = Token::create([
                'card' => [
                    'number' => $request->cardNumber,
                    'exp_month' => $expMonth,
                    'exp_year' => $expYear,
                    'cvc' => $request->cvc,
                ]
            ]);
            $email = Auth::user()->email;

            $customer = Customer::create([
                'email' => $email,
                'source' => $token,
            ]);

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $totalPrice * 100,
                'currency' => 'usd',
            ]);
        }

        $order = new Order();
        $order->user_id = Auth::id();
        $order->coupon_id = $request->couponUse === true ? $request->cartCoupon['id'] : null;
        $order->price = $price;
        $order->discount = $request->couponUse === true ? null : $discount;
        $order->shipping_fee = $request->shippingType === true ? $shipping['normal_price'] : $shipping['express_price'];
        $order->coupon = $request->couponUse === true ? $request->cartCoupon['fixed_offer'] !== null ? $request->cartCoupon['fixed_offer'] : ($price * $request->cartCoupon['percent_offer']) / 100 : null;
        $order->total_price = $totalPrice;
        $order->shipping_address = json_encode($request->selectAddress);
        $order->billing_address = $request->billingStatus === true ? json_encode($request->selectAddress) : json_encode($billingAddress);
        $order->payment_status = $request->paymentType === 'cod' ? false : true;
        $order->payment_type = $request->paymentType;
        $order->delivery_status = json_encode($deliveryStatus);
        $order->save();
        foreach ($carts as $data) {
            $orderProducts = new OrderProduct();
            $orderProducts->order_id = $order->id;
            $orderProducts->product_id = $data->product->id;
            $orderProducts->product_color = $data->color->name;
            $orderProducts->product_color_code = $data->color->color;
            $orderProducts->product_variant = $data->variant->variant;
            $orderProducts->product_quantity = $data->quantity;
            $orderProducts->product_price = $data->product->price;
            $orderProducts->save();
            $data->delete();
        }
        if ($request->couponUse === true) {
            $coupon = Coupon::findOrFail($request->cartCoupon['id'])->increment('total_use');
        }
        return response()->json($order->id);
    }

    public function order($id)
    {
        $order = Order::where('user_id', Auth::id())->with('user', 'products','products.product')->findOrFail($id);
        return response()->json($order);
    }

    public function get()
    {
        $order = Order::where('user_id', Auth::id())->with('products', 'products.product')->latest()->take(5)->get();
        return response()->json($order);
    }
}
