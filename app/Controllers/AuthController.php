<?php
// AuthController.php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\FacultyModel;
use App\Models\StudentModel;
use App\Models\ProgramModel;


class AuthController extends BaseController
{
    // DISPLAY LOGIN PAGE
    public function index()
    {        
        $userModel    = new UserModel();
        $username = session()->get('username');
        $user = $userModel->where('username', $username)->first();

        // IF USER IS ALREADY LOGGED IN, REDIRECT TO HOME
        if (session()->get('isLoggedIn')) {
            if ($user['role'] === 'student') {
                return redirect()->to('student/home');
            } elseif ($user['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } elseif (in_array($user['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            }
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
        // Check password
        if (password_verify($password, $user['userpassword'])) {

            // Update login timestamp and set status to active
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

            // Load extra profile info from role-specific table
            $details = [];
            if ($user['role'] === 'student') {
                $details = $studentModel->where('student_id', $user['username'])->first();
            } elseif ($user['role'] === 'faculty') {
                $details = $facultyModel->where('faculty_id', $user['username'])->first();
            } elseif (in_array($user['role'], ['admin', 'superadmin'])) {
                $details = $adminModel->where('admin_id', $user['username'])->first();
            }

            // If student, store program_id and year_level in session
            if ($user['role'] === 'student') {
                $sessionData['program_id'] = $details['program_id'] ?? null;
                $sessionData['year_level'] = $details['year_level'] ?? null;

                // 🟢 YES: ADD PROGRAM NAME TO SESSION (already present!)
                if (!empty($details['program_id'])) {
                    $programModel = new ProgramModel();
                    $program = $programModel->find($details['program_id']);
                    $sessionData['program'] = $program['program_name'] ?? null;
                }
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

            // Set session data
            $session->set($sessionData);

            // Redirect to home/dashboard
            if ($user['role'] === 'student') {
                return redirect()->to('student/home');
            } elseif ($user['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } elseif (in_array($user['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            }
            // return redirect()->to('admin/home');
            dd($sessionData); // <--- REMOVE after testing
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



