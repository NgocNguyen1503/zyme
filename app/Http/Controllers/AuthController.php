<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLogin(Request $request)
    {
        return view('login');
    }
    public function postLogin(Request $request)
    {
        $param = $request->all();
        if ($param['email'] && $param['password']) {
            if (Auth::attempt(['email' => $param['email'], 'password' => $param['password']])) {

                if (Auth::user()->role == User::ROLE_ADMIN) {
                    return redirect('/users');
                } else {
                    return redirect('/editor');
                }
            } else {
                return redirect('/login');
            }
        } else {
            return [
                'status' => 401,
                'message' => 'Unauthorization',
            ];
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
