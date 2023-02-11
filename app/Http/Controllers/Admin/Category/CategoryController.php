<?php

namespace App\Http\Controllers\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $data['limit'] = $request->limit ?? 10;
        $data['lists'] = Category::orderBy('id', 'ASC')->withTrashed()->paginate($data['limit']);
        return view('admin.category.index', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:4',
        ]);

        $request->merge(['slug' => Str::slug($request->title)]);
        $save = Category::Create($request->all());
        if ($save) {
            return redirect()->back()->with('success', 'Category Added');
        }
        return redirect()->back()->with('error', 'Something Went wrong');
    }

    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Category::find($id);
            if ($find) {
                $data['category'] = $find;
                return response()->json(['success' => true, 'message' => 'Edit Category', 'data' => view('admin.Category.edit', $data)->render()]);
            }
            return response()->json(['success' => false, 'message' => 'Category Not Found'], 404);
        }
        abort(403);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:4',
            'id'    => 'required',
            'slug'  => 'required',
        ]);

        $id = base64_decode($request->id);
        $find = Category::find($id);
        if ($find) {
            $find->title = $request->title;
            $find->slug =  Str::slug($request->slug);
            $find->save();
            return redirect()->back()->with('success', 'Category Updated');
        }
        return redirect()->back()->with('error', 'Category Not Found');
    }

    public function status(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Category::find($id);
            if ($find) {
                $find->status = $find->status == 1 ? 0 : 1;
                $find->save();
                return response()->json(['success' => true, 'message' => 'Category Status Changed'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Category Not Found'], 404);
        }
        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Category::find($id);
            if ($find) {
                $find->delete();
                return response()->json(['success' => true, 'message' => 'Category Deleted'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Category Not Found'], 404);
        }
        abort(403);
    }

    public function permanentDelete(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Category::onlyTrashed()->find($id);
            if ($find) {
                $find->forceDelete();
                return response()->json(['success' => true, 'message' => 'Category Deleted Permanently'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Category Not Found'], 404);
        }
        abort(403);
    }

    public function restore(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = Category::onlyTrashed()->find($id);
            if ($find) {
                $find->restore();
                return response()->json(['success' => true, 'message' => 'Category Restored'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Category Not Found'], 404);
        }
        abort(403);
    }
}
