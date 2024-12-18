<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller 
{
    public function showLoginPage(): View
    {
        return view('admin.auth.admin_login');
    }

    public function login(LoginRequest $request): RedirectResponse 
    {
        $credentials = $request->only(['email', 'password']); 
        
        if (Auth::guard('admin')->attempt($credentials)) 
        {
            $num = 0;
            return redirect()->intended('/admin/attendance/list/' . $num);
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
} 
