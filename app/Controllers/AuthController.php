<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginModel; // ✅ Import your LoginModel

class AuthController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        $session = session();
        $request = \Config\Services::request();
        $model = new LoginModel();

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // ✅ Get user from the database
        $user = $model->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // ✅ Set session data if password is correct
            $session->set([
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/dashboard');
        } else {
            // ❌ Login failed
            $session->setFlashdata('error', 'Invalid credentials.');
            return redirect()->to('/login')->withInput();
        }
    }
}
