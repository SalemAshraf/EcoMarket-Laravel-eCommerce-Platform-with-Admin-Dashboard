<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Vanilo\Cart\Facades\Cart;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size', 12);
        $order = $request->query('order', -1);
        $f_brands = $request->query('brands', '');
        $f_categories = $request->query('categories', '');
        $min_price = $request->query('min') ? $request->query('min') : 1;
        $max_price = $request->query('max') ? $request->query('max') : 10000;
        $sorting = '';

        $orderOptions = [
            1 => ['created_at', 'ASC'],
            2 => ['created_at', 'DESC'],
            3 => ['sale_price', 'ASC'],
            4 => ['sale_price', 'DESC'],
        ];
        [$o_column, $o_order] = $orderOptions[$order] ?? ['id', 'DESC'];

        // Fetch all brands and categories
        $brands = Brand::orderBy("name", "ASC")->get();
        $categories = Category::orderBy("name", "ASC")->get();

        // Build product query
        $productQuery = Product::query();
        if (!empty($f_brands)) {
            $brandIds = explode(',', $f_brands);
            $productQuery->whereIn('brand_id', $brandIds);
        }

        if (!empty($f_categories)) {
            $categoryIds = explode(',', $f_categories);
            $productQuery->whereIn('cat_id', $categoryIds);
        }
        if (!empty($min_price)) {
            $productQuery->where('sale_price', '>=', $min_price);
        }

        if (!empty($max_price)) {
            $productQuery->where('sale_price', '<=', $max_price);
        }

        $products = $productQuery->orderBy($o_column, $o_order)->paginate($size);

        return view('user.shop', compact("products", "brands", "min_price", "max_price", "categories", "f_brands", "f_categories", "sorting", "size", "order"));
    }
    public function product_details($product_slug)
    {
        $product = Product::where("slug", $product_slug)->first();
        $rproducts = Product::where("slug", "<>", $product_slug)->get()->take(8);
        return view('user.details', compact("product", "rproducts"));
    }
}
