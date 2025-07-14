<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status',1)->get()->take(3);
        $categories = Category::orderBy("name", "ASC")->get();
        $products = Product::orderBy('id', 'ASC')->paginate(8);
        $productsSales = Product::orderBy('sale_price', 'DESC')->paginate(8);
        return view('index', compact('slides', 'products', 'productsSales', 'categories'));
    }

}
