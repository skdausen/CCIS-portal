<?php

namespace App\Controllers;

use App\Models\LoginModel;

class AdminController extends BaseController
{
    //  DISPLAY ADMIN HOME PAGE
    public function adminHome()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        return view('templates/admin/admin_header')
            . view('admin/home')
            . view('templates/admin/admin_footer');
    }

    // DISPLAY LIST OF USERS
    public function users()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $model = new LoginModel();
        $data['users'] = $model->findAll(); // Fetch all users

        return view('templates/admin/admin_header')
            . view('admin/users', $data)
            . view('templates/admin/admin_footer');
    }

    // SHOW FORM TO ADD USER
    public function addUserForm()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        return view('templates/admin/admin_header')
            . view('admin/add_users')
            . view('templates/admin/admin_footer');
    }

    // HANDLE SUBMISSION OF NEW USER FORM
    public function createUser()
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'superadmin'])) {
            return redirect()->to('auth/login');
        }

        $model = new LoginModel();

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email'); // 
        $role     = $this->request->getPost('role');

        // UNIVERSAL DEFAULT PASSWORD
        $defaultPassword = 'ccis1234';
        $hashedPassword  = password_hash($defaultPassword, PASSWORD_DEFAULT);

        // CHECK IF USERNAME OR EMAIL ALREADY EXISTS
        if ($model->where('username', $username)->first()) {
            return redirect()->back()->with('error', 'Username already exists.');
        }

        if ($model->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email already exists.');
        }

        // INSERT NEW USER RECORD
        $model->insert([
            'username'     => $username,
            'email'        => $email,
            'userpassword' => $hashedPassword,
            'role'         => $role,
            'status'       => 'inactive',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('admin/users')->with('success', 'Account created successfully.');
    }

    public function viewUser($id)
    {
        $model = new LoginModel();
        $user = $model->find($id);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found.');
        }

        return view('templates/admin/admin_header')
            . view('admin/view_user', ['user' => $user])
            . view('templates/admin/admin_footer');
    }

}
