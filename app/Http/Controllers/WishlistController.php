<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function add_to_wishlist(Request $request)
    {
        $product = Product::findOrFail($request->id); // جلب المنتج من قاعدة البيانات

        // تأكد أن السعر صالح
        if (!is_numeric($product->regular_price) || $product->regular_price <= 0) {
            return back()->with('error', 'This product has an invalid price.');
        }

        Cart::instance('wishlist')->add(
            $product->id,
            $product->name,
            $request->quantity,
            floatval($product->regular_price)
        )->associate(Product::class);

        return redirect()->back()->with('success', 'Product added to wishlist!');
    }
    public function remove_item_from_wishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }
    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }
    public function index()
    {
        $cartItems = Cart::instance('wishlist')->content();
        return view('user.wishlist', compact('cartItems'));
    }
    public function move_to_cart($rowId)
{
       $item = Cart::instance('wishlist')->get($rowId);
       Cart::instance('wishlist')->remove($rowId);
       Cart::instance('cart')->add($item->id,$item->name,1,$item->price)->associate('App\Models\Product');
       return redirect()->back();
}
}
