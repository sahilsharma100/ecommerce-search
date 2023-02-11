<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashBoardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $products = Product::whereHas('categories', function ($query) use($search)
        {
            $query->where('title','like', '%'. $search.'%');
        })->orWhere('title', 'like', '%'.$search.'%')->paginate(10);

        $products->appends(['search' => $search]);
        return view('client.dashboard', compact('products', 'search'));
    }
}
