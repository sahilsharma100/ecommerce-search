<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    // Dashboard
    public function dashboard(Request $request)
    {
        $search = $request->query('search');
        $products = Product::whereHas('categories', function ($query) use($search)
        {
            $query->where('title','like', '%'. $search.'%');
        })->orWhere('title', 'like', '%'.$search.'%')->paginate(10); 
        
        $products->appends(['search' => $search]);
        return view('admin.index', compact('products', 'search'));
    }

}