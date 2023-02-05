<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('auth.login');
        } else {
            return redirect()->route('dashboard');
        }
    }


    public function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, true)) {
            $token = md5(uniqid());

            User::where('id', '=', Auth::id())->update(['token' => $token, 'user_status' => 'online']);

            return redirect()->route('dashboard');
        }

        return redirect()->back()->with('success', 'Login details are not valid');
    }

    public function dashboard()
    {
        return view('home.dashboard');
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('login');
    }
}
