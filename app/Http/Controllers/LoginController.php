<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\AdminUser;


class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $adminUser = new AdminUser();
        $adminUsers = $adminUser->get();
        $isValidated = false;
        foreach ($adminUsers as $user) {
            if ($request->input('login_id') == $user['login_id'] and $request->input('login_pass') == $user['login_pass']) {
                $isValidated = true;
                $request->session()->put('name', $user['name']);
            }
        }
        if ($isValidated) {
            $request->session()->put('authenticated', hash('sha256', session_id()));
            return view('top');
        } else {
            $wrongError = 'IDまたはパスワードが間違っています。';
            $loginId = $request->input('login_id');
            return view('login', compact('wrongError', 'loginId'));
        }

    }
}
