<?php //AuthController.php
namespace App\Controllers;

use App\Models\LoginModel;

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
        $model = new LoginModel(); // Simplified since you have 'use' statement

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        $user = $model->getUserByUsername($username);
        
        if (!$user) {
        // USER NOT FOUND
        $session->setFlashdata('error', 'User does not exist.');
        return redirect()->to('login')->withInput();
        }
        // password hash verification
        if (password_verify($password, $user['userpassword'])) {
            $model->update($user['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

            $session->set([
                'user_id'    => $user['user_id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true
            ]);

            return redirect()->to('home');
        } else {
            $session->setFlashdata('error', 'Incorrect password.');
            return redirect()->to('login')->withInput();
        }
    }

    public function home()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        return view('home');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}