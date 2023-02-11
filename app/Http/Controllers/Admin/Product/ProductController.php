<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data['limit'] = $request->limit ?? 10;
        $data['lists'] = Product::with('image')->orderBy('id', 'ASC')->withTrashed()->paginate($data['limit']);
        $data['categories'] = Category::orderBy('title', 'ASC')->whereStatus(1)->get();
        return view('admin.product.index', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:4',
            'description' => 'required|min:4',
            'img.*'   =>  'required|file|mimes:jpg,jpeg,png,gif|max:5024',
        ]);

        $request->merge(['slug' => Str::slug($request->title)]);
        $save = Product::Create($request->all());
        if ($save) {
            if($request->file('img')){
                self::uploadFiles('uploads/products/', $request->file('img'), false, $save->id);
            }
            $save->categories()->sync($request->input('categories', []));
            return redirect()->back()->with('success', 'Product Added');
        }
        return redirect()->back()->with('error', 'Something Went wrong');
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Product::with('images')->find($id);
            if ($find) {
                $data['product'] = $find;
                $data['categories'] = Category::orderBy('title', 'ASC')->whereStatus(1)->get();
                return response()->json(['success' => true, 'message' => 'Edit Product', 'data' => view('admin.product.edit', $data)->render()]);
            }
            return response()->json(['success' => false, 'message' => 'Product Not Found'], 404);
        }
        abort(403);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:4',
            'id'    => 'required',
            'slug'  => 'required',
            'description' => 'required|min:4',
            'img.*'   =>  'required|file|mimes:jpg,jpeg,png,gif|max:5024',
        ]);

        $id = base64_decode($request->id);
        $find = Product::find($id);
        if ($find) {
            $find->title = $request->title;
            $find->description = $request->description;
            $find->slug =  Str::slug($request->slug);
            $find->save();

            if($request->file('img')){
                self::uploadFiles('uploads/products/', $request->file('img'), false, $find->id);
            }

            $find->categories()->sync($request->input('categories', []));
            return redirect()->back()->with('success', 'Product Updated');
        }
        return redirect()->back()->with('error', 'Product Not Found');
    }

    public function status(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Product::find($id);
            if ($find) {
                $find->status = $find->status == 1 ? 0 : 1;
                $find->save();
                return response()->json(['success' => true, 'message' => 'Product Status Changed'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Product Not Found'], 404);
        }
        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Product::find($id);
            if ($find) {
                $find->delete();
                return response()->json(['success' => true, 'message' => 'Product Deleted'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Product Not Found'], 404);
        }
        abort(403);
    }

    public function permanentDelete(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Product::onlyTrashed()->find($id);
            if ($find) {
                $find->forceDelete();
                return response()->json(['success' => true, 'message' => 'Product Deleted Permanently'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Product Not Found'], 404);
        }
        abort(403);
    }

    public function restore(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Product::onlyTrashed()->find($id);
            if ($find) {
                $find->restore();
                return response()->json(['success' => true, 'message' => 'Product Restored'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Product Not Found'], 404);
        }
        abort(403);
    }

    private function uploadFiles($path, $files, $old, $id)
    {
        $insert = [];

        if(!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        if($old){
            file_exists($old) && is_file($old) ? @unlink($old) : false;
        }
        
        foreach ($files as $key => $file) {
            $ext = $file->getClientOriginalExtension();
            $data = [
                'product_id'    => $id,
                'type'          => $ext ,
                'path'          => $path,
                'file'          => uniqid('product-').'-'. time() . '.' . $ext,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ];

            //Move Uploaded File
            $file->move($path, $data['file']);
            $insert[] = $data;
        }

        if(count($insert) > 0){
            Image::insert($insert);
        }
    }
}
