<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        // Query builder: thao tác trực tiếp tới bảng CSDL bằng lệnh SQL
        $data = DB::table('users')
            ->select(
                'id', 'email', 'name', 'created_at', 'role'
            )
            ->where('role', User::ROLE_ADMIN)
            ->get();
        if (!empty($data))
        {
            foreach ($data as $user)
            {
                $user->role = 'Admin';
            }
        }
        return $data;
    }

    public function listFile(Request $request, $editorId)
    {
        $job = DB::table('files')
            ->select(
                'users.name', 'files.filename', 'files.deadline',
                'files.status', 'files.priority', 'files.synchronize',
                'files.id'
            )
            ->join('users', 'users.id', 'files.user_id')
            ->where('users.id', $editorId)
            ->where('users.role', User::ROLE_EDITOR)
            ->orderByDesc('files.id')
            ->paginate(config('const.paginate'))
            ->map(function ($item)
            {
                $item->status = File::CONVERT_STATUS_TXT[$item->status];
                $item->priority = File::CONVERT_PRIORITY_TXT[$item->priority];
                $item->synchronize = File::CONVERT_SYNCHRONIZE_TXT[$item->synchronize];
                return $item;
            });
        return $job;
    }

    public function sortUserFile(Request $request)
    {
        $param = $request->all();
        $users = DB::table('users')
            ->select(
                'users.id', 'users.name', 'users.email',
                DB::raw('COUNT(files.id) AS total_file')
                )
            ->join('files', 'users.id', 'files.user_id')
            ->where('users.role', User::ROLE_EDITOR)
            ->groupBy('users.id')
            ->groupBy('users.name')
            ->groupBy('users.email')
            ->orderBy('total_file', isset($param['sort']) ? $param['sort'] : 'DESC')
            ->orderByDesc('users.id')
            ->get();
        return $users;
    }

    public function eloquent(Request $request)
    {
        $param = $request->all();
        // Eloquent sẽ render ra lệnh SQL thông qua model
        $users = User::with('files')
            ->when(function ($query) use ($param)
            {
                if (isset($param['editor_name']) && isset($param['file_name']))
                {
                    $query->where('name', 'like', '%' . $param['editor_name'] . '%');
                }
                return $query;
            })
            ->whereHas('files', function ($query) use ($param)
            {
                if (isset($param['file_name']) && isset($param['editor_name']))
                {
                    $query->where('filename', 'like', '%' . $param['file_name'] . '%');
                }
                return $query;
            })
            ->where('role', User::ROLE_EDITOR)
            ->get()
            ->map(function ($item)
            {
                foreach($item->files as $file)
                {
                    $file->status = File::CONVERT_STATUS_TXT[$file->status];
                    $file->priority = File::CONVERT_PRIORITY_TXT[$file->priority];
                    $file->synchronize = File::CONVERT_SYNCHRONIZE_TXT[$file->synchronize];
                }
                return $item;
            });
        return view('demo_eloquent', compact('users'));
    }
}