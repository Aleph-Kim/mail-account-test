<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('authenticated')) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (
            $request->username === env('AUTH_USERNAME') &&
            $request->password === env('AUTH_PASSWORD')
        ) {
            $request->session()->regenerate();
            session(['authenticated' => true]);

            return redirect()->intended('/');
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['credentials' => '아이디 또는 비밀번호가 올바르지 않습니다.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('authenticated');

        return redirect()->route('login');
    }
}
