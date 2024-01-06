<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginForm(): View
    {
        return view('auth.login');
    }
}
