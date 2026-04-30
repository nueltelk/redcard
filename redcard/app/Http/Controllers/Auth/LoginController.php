<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->only('username', 'password'))) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput($request->only('username'));
        }

        $request->session()->regenerate();

        return auth()->user()->role === 'admin'
            ? redirect()->intended('/admin')
            : redirect()->intended('/dashboard');
    }
}