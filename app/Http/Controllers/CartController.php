<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::instance('cart')->content();
        return view('cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        session()->flash('success', 'Product is Added to Cart Successfully !');
        return back();
    }

    public function increase_item_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }
    public function reduce_item_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }
    public function remove_item_from_cart($rowId)
{
    Cart::instance('cart')->remove($rowId);
    return redirect()->back();
}
public function empty_cart()
{
    Cart::instance('cart')->destroy();
    return redirect()->back();
}
public function apply_coupon_code(Request $request)
{
    $coupon_code = $request->coupon_code;

    if (!$coupon_code) {
        return back()->with('error', 'Please enter a coupon code.');
    }

    $coupon = Coupon::where('code', $coupon_code)
        ->where('expire_date', '>=', Carbon::today())
        ->first();

    if (!$coupon) {
        return back()->with('error', 'Invalid or expired coupon code!');
    }

    $cartSubtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));

    if ($cartSubtotal < $coupon->cart_value) {
        return back()->with('error', 'Your cart total must be at least EGP' . $coupon->cart_value . ' to use this coupon.');
    }

    // Store coupon in session
    session()->put('coupon', [
        'code' => $coupon->code,
        'type' => $coupon->type,
        'value' => $coupon->value,
        'cart_value' => $coupon->cart_value
    ]);

    $this->calculateDiscounts();

    return back()->with('success', 'Coupon code has been applied!');
}

public function calculateDiscounts()
{
    if (!session()->has('coupon')) {
        return;
    }

    $cartSubtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));
    $coupon = session()->get('coupon');

    $discount = $coupon['type'] === 'fixed'
        ? floatval($coupon['value'])
        : ($cartSubtotal * floatval($coupon['value']) / 100);

    $subtotalAfterDiscount = $cartSubtotal - $discount;
    $taxRate = config('cart.tax'); // تأكد أنه عدد وليس string
    $tax = $subtotalAfterDiscount * $taxRate / 100;
    $total = $subtotalAfterDiscount + $tax;

    session()->put('discounts', [
        'discount' => number_format($discount, 2, '.', ''),
        'subtotal' => number_format($subtotalAfterDiscount, 2, '.', ''),
        'tax' => number_format($tax, 2, '.', ''),
        'total' => number_format($total, 2, '.', '')
    ]);
}
public function remove_coupon_code()
{
    session()->forget('coupon');
    session()->forget('discounts');
    return back()->with('status','Coupon has been removed!');
}

public function checkout()
{
    if(!FacadesAuth::check())
    {
        return redirect()->route("login");
    }
    $address = Address::where('user_id',Auth::user()->id)->where('isdefault',1)->first();
    return view('user.checkout',compact("address"));
}
public function place_order(Request $request)
{
    $user_id = Auth::user()->id;
    $address = Address::where('user_id',$user_id)->where('isdefault',true)->first();
    if(!$address)
    {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:10',
            'zip' => 'required|numeric|digits:6',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'landmark' => 'required'
        ]);
        $address = new Address();
        $address->user_id = $user_id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->address = $request->address;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->country = '';
        $address->isdefault = true;
        $address->save();
    }
    $this->setAmountForCheckout();
    $order = new Order();
    $order->user_id = $user_id;
    $order->subtotal = session()->get('checkout')['subtotal'];
    $order->discount = session()->get('checkout')['discount'];
    $order->tax = session()->get('checkout')['tax'];
    $order->total = session()->get('checkout')['total'];
    $order->name = $address->name;
    $order->phone = $address->phone;
    $order->locality = $address->locality;
    $order->address = $address->address;
    $order->city = $address->city;
    $order->state = $address->state;
    $order->country = $address->country;
    $order->landmark = $address->landmark;
    $order->zip = $address->zip;
    $order->save();
    foreach(Cart::instance('cart')->content() as $item)
    {
        $orderitem = new OrderItem();
        $orderitem->product_id = $item->id;
        $orderitem->order_id = $order->id;
        $orderitem->price = $item->price;
        $orderitem->quantity = $item->qty;
        $orderitem->save();
    }
    $transaction = new Transaction();
    $transaction->user_id = $user_id;
    $transaction->order_id = $order->id;
    $transaction->mode = $request->mode;
    $transaction->status = "pending";
    $transaction->save();

    Cart::instance('cart')->destroy();
    session()->forget('checkout');
    session()->forget('coupon');
    session()->forget('discounts');
    Mail::to($order->user->email)->send(new OrderPlacedMail($order));
    return redirect()->route('cart.confirmation');
}
public function setAmountForCheckout()
{
    if(!Cart::instance('cart')->count() > 0)
    {
        session()->forget('checkout');
        return;
    }
    if(session()->has('coupon'))
    {
        session()->put('checkout',[
            'discount' => session()->get('discounts')['discount'],
            'subtotal' =>  session()->get('discounts')['subtotal'],
            'tax' =>  session()->get('discounts')['tax'],
            'total' =>  session()->get('discounts')['total']
        ]);
    }
    else
    {
        session()->put('checkout',[
            'discount' => 0,
            'subtotal' => Cart::instance('cart')->subtotal(),
            'tax' => Cart::instance('cart')->tax(),
            'total' => Cart::instance('cart')->total()
        ]);
    }
}
public function confirmation()
{
    return view('user.order-confirmation');
}
}
