<?php
// AuthController.php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\FacultyModel;
use App\Models\StudentModel;

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
    public function authenticate2()
    {
        $session = session();
        $request = \Config\Services::request();
        $model = new UserModel();

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
            // Update login timestamp and set status to active
            $model->update($user['user_id'], [
                'last_login' => date('Y-m-d H:i:s'),
                'status'     => 'active'
            ]);

            // Set full session data
            $session->set([
                'isLoggedIn'     => true,
                'user_id'        => $user['user_id'],
                'username'       => $user['username'],
                'email'          => $user['email'],
                'role'           => $user['role'],
                'last_login'     => date('Y-m-d H:i:s'), // updated login time
            ]);

            // Redirect based on role
            if (in_array($user['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            } else if ($user['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } else if ($user['role'] === 'student') {
                return redirect()->to('student/home');
            } else {
                return redirect()->to('home');
            }
        }
        // If password is incorrect
        $session->setFlashdata('error', 'Incorrect password.');
        return redirect()->to('auth/login')->withInput(); 
    }

    public function authenticate()
    {
        $session = session();
        $request = \Config\Services::request();
        $userModel = new UserModel();
        $studentModel = new StudentModel();
        $facultyModel = new FacultyModel();
        $adminModel = new AdminModel();

        $username = $request->getPost('username');
        $password = $request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['userpassword'])) {
            return redirect()->to('auth/login')->with('error', 'Invalid credentials.')->withInput();
        }

        // Default data from users table
        $sessionData = [
            'user_id'     => $user['user_id'],
            'username'    => $user['username'],
            'email'       => $user['email'],
            'role'        => $user['role'],
            'last_login'  => $user['last_login'],
            'isLoggedIn'  => true
        ];

        // 🔍 Load data based on role
        if ($user['role'] === 'student') {
            $details = $studentModel->where('student_id', $user['username'])->first();
        } elseif ($user['role'] === 'faculty') {
            $details = $facultyModel->where('faculty_id', $user['username'])->first();
        } elseif (in_array($user['role'], ['admin', 'superadmin'])) {
            $details = $adminModel->where('admin_id', $user['username'])->first();
        } else {
            $details = [];
        }

        // 👇 Merge additional profile info
        if (!empty($details)) {
            $sessionData = array_merge($sessionData, [
                'fname'             => $details['fname'] ?? null,
                'mname'             => $details['mname'] ?? null,
                'lname'             => $details['lname'] ?? null,
                'sex'               => $details['sex'] ?? null,
                'birthdate'         => $details['birthdate'] ?? null,
                'address'           => $details['address'] ?? null,
                'contactnum'        => $details['contactnum'] ?? null,
                'profimg'      => $details['profimg'] ?? 'default.png',

            ]);
        }

        // 💾 Save all to session
        $session->set($sessionData);

        // 🕒 Optionally update last_login
        $userModel->update($user['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

        return redirect()->to('admin/home');
    }



    // DISPLAY HOME PAGE (PROTECTED)
    public function home()
    {
        $session = session();
        $model = new UserModel();   

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
        $model = new UserModel();

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
