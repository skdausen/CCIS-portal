<?php
// AuthController.php

namespace App\Controllers;

use App\Models\LoginModel;

class AuthController extends BaseController
{
    // DISPLAY LOGIN PAGE
    public function index()
    {
        // IF USER IS ALREADY LOGGED IN, REDIRECT TO HOME
        if (session()->get('isLoggedIn')) {
            return redirect()->to('home');
        }

        // SHOW LOGIN PAGE WITH HEADER AND FOOTER
        return view('templates/login_header')
            . view('auth/login')
            . view('templates/login_footer');
    }

    // HANDLE LOGIN FORM SUBMISSION
    public function authenticate()
    {
        $session = session(); // START SESSION
        $request = \Config\Services::request(); // GET POST REQUEST
        $model = new LoginModel(); // LOAD LOGIN MODEL

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // FIND USER BY USERNAME
        $user = $model->getUserByUsername($username);

        // IF USER NOT FOUND
        if (!$user) {
            $session->setFlashdata('error', 'User does not exist.');
            return redirect()->to('auth/login')->withInput();
        }

        // VERIFY PASSWORD
        if (password_verify($password, $user['userpassword'])) {
            // UPDATE LAST LOGIN TIMESTAMP
            $model->update($user['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

            // SET SESSION DATA
            $session->set([
                'user_id'    => $user['user_id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true
            ]);

            // REDIRECT TO HOME PAGE
            return redirect()->to('home');
        } else {
            // WRONG PASSWORD
            $session->setFlashdata('error', 'Incorrect password.');
            return redirect()->to('auth/login')->withInput();
        }
    }

    // DISPLAY HOME PAGE (PROTECTED)
    public function home()
    {
        // CHECK IF USER IS LOGGED IN
        if (session()->get('isLoggedIn')) {
            echo "SESSION NOT FOUND!";
            // CHECK IF USER ROLE IS ALLOWED (admin or superadmin)
            $role = session()->get('role');
            if ($role == 'admin' && $role == 'superadmin') {
                return redirect()->to('home');
            }
        }

        // USER IS LOGGED IN AND HAS THE RIGHT ROLE
        return view('auth/login');
    }



    // LOGOUT FUNCTION
    public function logout()
    {
        // DESTROY ALL SESSION DATA
        session()->destroy();

        // REDIRECT TO LOGIN PAGE
        return redirect()->to('auth/login');
    }

}
