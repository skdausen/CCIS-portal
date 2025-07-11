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

        // Load models
        $userModel    = new UserModel();
        $studentModel = new StudentModel();
        $facultyModel = new FacultyModel();
        $adminModel   = new AdminModel();

        // Get login form input
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // Find user by username
        $user = $userModel->where('username', $username)->first();

        // User not found
        if (!$user) {
            $session->setFlashdata('error', 'User does not exist.');
            return redirect()->to('auth/login')->withInput();
        }

        // If user is already marked as active
        if ($user['status'] === 'active') {
            $session->setFlashdata('error', 'User is already logged in elsewhere.');
            return redirect()->to('auth/login')->withInput();
        }

        // Check password
        if (password_verify($password, $user['userpassword'])) {

            // ✅ Update login timestamp and set status to active
            $userModel->update($user['user_id'], [
                'last_login' => date('Y-m-d H:i:s'),
                'status'     => 'active'
            ]);

            // Session data from users table
            $sessionData = [
                'user_id'     => $user['user_id'],
                'username'    => $user['username'],
                'email'       => $user['email'],
                'role'        => $user['role'],
                'status'      => 'active', // already updated
                'created_at'  => $user['created_at'],
                'last_login'  => date('Y-m-d H:i:s'),
                'isLoggedIn'  => true
            ];

            // 🔍 Load extra profile info from role-specific table
            $details = [];
            if ($user['role'] === 'student') {
                $details = $studentModel->where('student_id', $user['username'])->first();
            } elseif ($user['role'] === 'faculty') {
                $details = $facultyModel->where('faculty_id', $user['username'])->first();
            } elseif (in_array($user['role'], ['admin', 'superadmin'])) {
                $details = $adminModel->where('admin_id', $user['username'])->first();
            }

            // Merge additional fields into session
            if (!empty($details)) {
                $sessionData = array_merge($sessionData, [
                    'fname'      => $details['fname'] ?? null,
                    'mname'      => $details['mname'] ?? null,
                    'lname'      => $details['lname'] ?? null,
                    'sex'        => $details['sex'] ?? null,
                    'birthdate'  => $details['birthdate'] ?? null,
                    'address'    => $details['address'] ?? null,
                    'contactnum' => $details['contactnum'] ?? null,
                    'profimg'    => $details['profimg'] ?? 'default.png',
                ]);
            }

            // 💾 Set session data
            $session->set($sessionData);

            // Redirect to home/dashboard
            return redirect()->to('admin/home');
        } else {
            // Wrong password
            $session->setFlashdata('error', 'Incorrect Password.');
            return redirect()->to('auth/login')->withInput();
        }
    }

    // LOGOUT USER
    public function logout()
    {
        $session = session();               
        $userModel = new UserModel();   
        $userId = $session->get('user_id');
        if ($userId) {
            // Update user status to inactive
            $userModel->update($userId, [
                'status' => 'inactive'
            ]);
        }   
        // Destroy session
        $session->destroy();    

        // Redirect to login page                   

        return redirect()->to('auth/login')->with('success', 'You have been logged out successfully.');     

    }           
}    



