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
            'birthday'        => $this->request->getPost('birthday'),
            'address'         => $this->request->getPost('address'),
            'contact_number'  => $this->request->getPost('contact_number'),
        ];

        // Upload profile image if valid
        $img = $this->request->getFile('profile_img');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move('rsc/assets/uploads', $newName);
            $data['profile_img'] = $newName;
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
            'birthdate'   => $data['birthday'],
            'sex'         => $data['sex'],
            'address'     => $data['address'],
            'contactnum'  => $data['contact_number'],
            'profimg'     => $data['profimg'] ?? session('profimg'),
        ];

        // Remove null/empty values
        $roleData = array_filter($roleData, fn($val) => $val !== null && $val !== '');

        // ✅ Use correct model per role
        if (!empty($roleData)) {
            switch ($role) {
                case 'student':
                    $studentModel->update($userId, $roleData);
                    break;
                case 'faculty':
                    $facultyModel->update($userId, $roleData);
                    break;
                case 'admin':
                case 'superadmin':
                    $adminModel->update($userId, $roleData);
                    break;
            }
        }

        // Update session with new data
        session()->set(array_merge($data, [
            'profile_img' => $data['profile_img'] ?? session('profile_img'),
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

}
