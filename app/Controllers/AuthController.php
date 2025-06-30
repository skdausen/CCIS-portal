<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // Example: Hardcoded for demo
        if ($username === 'admin' && $password === 'secret') {
            $session->set('isLoggedIn', true);
            return redirect()->to('/dashboard');
        } else {
            $session->setFlashdata('error', 'Invalid credentials.');
            return redirect()->to('/login')->withInput();
        }
    }
}
