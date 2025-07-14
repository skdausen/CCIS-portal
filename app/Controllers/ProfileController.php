<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\FacultyModel;
use App\Models\StudentModel;

class ProfileController extends BaseController
{
    public function update()
    {
        $userModel     = new UserModel();
        $studentModel  = new StudentModel();
        $facultyModel  = new FacultyModel();
        $adminModel    = new AdminModel();

        $userId = session('user_id');
        $role   = session('role');

        // Get all common input
        $data = [
            'fname'           => $this->request->getPost('fname'),
            'mname'           => $this->request->getPost('mname'),
            'lname'           => $this->request->getPost('lname'),
            'email'           => $this->request->getPost('email'),
            'sex'             => $this->request->getPost('sex'),
            'birthdate'        => $this->request->getPost('birthdate'),
            'address'         => $this->request->getPost('address'),
            'contactnum'     => $this->request->getPost('contactnum'),
        ];

        // Upload profile image if valid
        $img = $this->request->getFile('profimg');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move('rsc/assets/uploads', $newName);
            $data['profimg'] = $newName;
        }

        // ✅ Only update email in users table
        $userModel->update($userId, [
            'email' => $data['email']
        ]);

        // Role-specific data
        $roleData = [
            'fname'       => $data['fname'],
            'mname'       => $data['mname'],
            'lname'       => $data['lname'],
            'birthdate'   => $data['birthdate'],
            'sex'         => $data['sex'],
            'address'     => $data['address'],
            'contactnum'  => $data['contactnum'],
            'profimg'     => $data['profimg'] ?? session('profimg'),
        ];

        // Remove null/empty values
        $roleData = array_filter($roleData, fn($val) => $val !== null && $val !== '');

        // ✅ Use correct model per role
        if (!empty($roleData)) {
            switch ($role) {
                case 'student':
                    $student = $studentModel->where('user_id', $userId)->first();
                    if ($student) {
                        $studentModel->update($student['stb_id'], $roleData);
                    }
                    break;

                case 'faculty':
                    $faculty = $facultyModel->where('user_id', $userId)->first();
                    if ($faculty) {
                        $facultyModel->update($faculty['ftb_id'], $roleData);
                    }
                    break;

                case 'admin':
                case 'superadmin':
                    $admin = $adminModel->where('user_id', $userId)->first();
                    if ($admin) {
                        $adminModel->update($admin['atb_id'], $roleData);
                    }
                    break;
            }

        }

        // Update session with new data
        session()->set(array_merge($data, [
            'profimg' => $data['profimg'] ?? session('profimg'),
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully!')->with('open_modal', 'profileModal');
    }

    public function update_password()
    {
        $userModel = new UserModel();
        $session = session();

        $userId = $session->get('user_id'); // or however you're storing it
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Get the current hashed password from DB
        $user = $userModel->find($userId);

        if (!$user || !password_verify($currentPassword, $user['userpassword'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.')->with('open_modal', 'profileModal');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match.')->with('open_modal', 'profileModal');
        }

        if (strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'New password must be at least 8 characters.')->with('open_modal', 'profileModal');
        }

        // Update password
        $userModel->update($userId, [
            'userpassword' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!')->with('open_modal', 'profileModal');
    }


}
