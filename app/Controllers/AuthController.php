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
        $session = session();
        $request = \Config\Services::request();
        $model = new LoginModel();

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        $user = $model->getUserByUsername($username);

        if (!$user) {
            $session->setFlashdata('error', 'User does not exist.');
            return redirect()->to('auth/login')->withInput();
        }

        //  If user is already logged in (status = active), block login
        if ($user['status'] === 'active') {
            $session->setFlashdata('error', 'User is already logged in elsewhere.');
            return redirect()->to('auth/login')->withInput();
        }

        //  Verify password
        if (password_verify($password, $user['userpassword'])) {
            //  Update login timestamp and set status to active
            $model->update($user['user_id'], [
                'last_login' => date('Y-m-d H:i:s'),
                'status'     => 'active'
            ]);

            //  Set session data
            $session->set([
                'user_id'    => $user['user_id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true
            ]);

            //  Role-based redirect
            if (in_array($user['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            } 
            else if (in_array($user['role'], ['faculty'])) {
                return redirect()->to('faculty/home');
            }
             else {
                return redirect()->to('home');
            }
        } else {
            $session->setFlashdata('error', 'Incorrect password.');
            return redirect()->to('auth/login')->withInput();
        }
    }


    // DISPLAY HOME PAGE (PROTECTED)
    public function home()
    {
        $session = session();
        $model = new LoginModel();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('auth/login');
        }

        $user = $model->find($session->get('user_id'));

        // If user is not active, redirect
        if (!$user || $user['status'] !== 'active') {
            $session->destroy();
            return redirect()->to('auth/login')->with('error', 'Session expired or unauthorized access.');
        }

        return view('home');
    }

    // LOGOUT FUNCTION
    public function logout()
    {
        $userId = session()->get('user_id');
        $model = new LoginModel();

        if ($userId) {
            // 📴 Set user status to inactive
            $model->update($userId, ['status' => 'inactive']);
        }

        // Destroy session
        session()->destroy();

        // Redirect to login
        return redirect()->to('auth/login');
    }


}
