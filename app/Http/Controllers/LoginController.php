<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\AdminUser;


class LoginController extends Controller
{

    public function store(LoginRequest $request)
    {
        try {
            $adminUser = new AdminUser();
            $adminUsers = $adminUser->get();
            $isValidated = false;
            foreach ($adminUsers as $user) {
                if ($request->input('login_id') == $user['login_id'] and $request->input('login_pass') == $user['login_pass']) {
                    $isValidated = true;
                    session()->put('name', $user['name']);
                    session()->put('user_id', $user['id']);
                }
            }
            if ($isValidated) {
                session()->put('authenticated', hash('sha256', session_id()));
                return view('top');
            } else {
                $otherError = 'ログインIDまたはパスワードが間違っています。';
                $loginId = $request->input('login_id');
                return view('login', compact('otherError', 'loginId'));
            }
        } catch (PDOException $e) {
            $otherError = 'データベースに接続できませんでした。';
            return view('login', compact('otherError'));
        }

    }

    public function logout()
    {
        session()->flush();
        return view('login');
    }
}
