<?php

namespace App\Http\Controllers\Admin\UserManagement\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($items = 10)
    {
        $data['items'] = $items;
        $data['lists'] = User::orderBy('id', 'ASC')->withTrashed()->paginate($items);
        return view('admin.user-management.user.index', $data);
    }
    
    public function status(Request $request, $id)
    {
        if ($request->ajax()) {
            $id = base64_decode($id);
            $find = User::find($id);
            if ($find) {
                $find->status = $find->status == 1 ? 0 : 1;
                $find->save();
                return response()->json(['success' => true, 'message' => 'User Status Changed'], 200);
            }
            return response()->json(['success' => false, 'message' => 'User Not Found'], 404);
        }
        abort(403);
    }
}
