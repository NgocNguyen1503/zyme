<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        $param = $request->all();
        $userId = Auth::user()->id;
        // Gender giao dien html
        $files = File::where('user_id', $userId)
            ->where(function ($query) use ($param) {
                if (isset($param['month']) && !is_null($param['month'])) {
                    $carbon = Carbon::create($param['month']);
                    return $query->whereMonth('created_at', $carbon);
                } else {
                    // Get current month
                    $carbon = new Carbon();
                    return $query->whereMonth('created_at', $carbon);
                }
            })->get()
            ->map(function ($item) {
                $item->txt_priority = File::CONVERT_PRIORITY_TXT[$item->priority];
                return $item;
            });
        return view('editor.index', compact('files'));
    }

    public function update(Request $request, $id)
    {
        $param = $request->all();
        $file = File::find($id);
        $file->status = isset($param['status']) ? File::STATUS_CONFIRM : File::STATUS_ASSIGN;
        $file->save();
        return redirect()->back();
    }
}
