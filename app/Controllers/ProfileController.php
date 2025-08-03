<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\FacultyModel;
use App\Models\StudentModel;
use App\Models\ProgramModel;

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

        $clearImage = $this->request->getPost('clear_image');
        $data = [
            'fname'      => ucwords(strtolower($this->request->getPost('fname'))),
            'mname'      => ucwords(strtolower($this->request->getPost('mname'))),
            'lname'      => ucwords(strtolower($this->request->getPost('lname'))),
            'email'      => $this->request->getPost('email'),
            'sex'        => $this->request->getPost('sex'),
            'birthdate'  => $this->request->getPost('birthdate'),
            'address'    => ucwords(strtolower($this->request->getPost('address'))),
            'contactnum' => $this->request->getPost('contactnum'),
        ];

        // Handle profile image logic
        if ($clearImage == '1') {
            $data['profimg'] = 'default.png';
        } else {
            $img = $this->request->getFile('profimg');
            if ($img && $img->isValid() && !$img->hasMoved()) {
                // CHECK FILE SIZE (1MB = 1048576 bytes)
                if ($img->getSize() > 1048576) {
                    return redirect()->back()->with('error', 'Image must not exceed 1MB.');
                }
                $mimeType = $img->getMimeType(); // Get actual MIME type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                if (!in_array($mimeType, $allowedTypes)) {
                    // Not an allowed file type
                    return redirect()->back()->with('error', 'Only JPG, JPEG, or PNG images are allowed.');
                }

                $image = null;

                if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                    $image = imagecreatefromjpeg($img->getTempName());
                } elseif ($mimeType === 'image/png') {
                    $image = imagecreatefrompng($img->getTempName());
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }

                if ($image) {
                    $randomName = pathinfo($img->getRandomName(), PATHINFO_FILENAME);
                    $webpName = $randomName . '.webp';
                    $destination = FCPATH . 'rsc/assets/uploads/' . $webpName;

                    imagewebp($image, $destination, 80); // compress and save
                    imagedestroy($image);

                    // SET new image
                    $data['profimg'] = $webpName;

                    // DELETE old image (if not default.png)
                    $oldImage = session('profimg');
                    if ($oldImage && $oldImage !== 'default.png') {
                        $oldPath = FCPATH . 'rsc/assets/uploads/' . $oldImage;
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                }
                else {
                    $data['profimg'] = session('profimg') ?? 'default.png';
                }
            } else {
                $data['profimg'] = session('profimg') ?? 'default.png';
            }
        }

        //  Update users table
        $userModel->update($userId, [
            'email'   => $data['email'],
            'profimg' => $data['profimg']
        ]);

        //  Role-specific data
        $roleData = [
            'fname'           => $data['fname'],
            'mname'           => $data['mname'],
            'lname'           => $data['lname'],
            'birthdate'       => $data['birthdate'],
            'sex'             => $data['sex'],
            'address'         => $data['address'],
            'contactnum'      => $data['contactnum'],
            'profimg'         => $data['profimg'],
            'program_id'      => $this->request->getPost('program_id'),
            'year_level'      => $this->request->getPost('year_level'),
            'employee_status' => $this->request->getPost('employee_status') ?? null,
        ];
        $roleData = array_filter($roleData, fn($val) => $val !== null && $val !== '');

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

        //  Get program name
        $programName = null;
        $programId = $this->request->getPost('program_id');

        if ($role === 'student' && $programId) {
            $programModel = new ProgramModel();

            // to tell the vscode this is an array
            /** @var array $program */
            $program = $programModel->asArray()->find($programId);
            if ($program) {
                $programName = $program['program_name'];
            }
        }

        //  Update session
        session()->set(array_merge($data, [
            'profimg'         => $data['profimg'],
            'program_id'      => $this->request->getPost('program_id'),
            'program'         => $programName,
            'year_level'      => $this->request->getPost('year_level'),
            'employee_status' => $this->request->getPost('employee_status')
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully!')->with('open_modal', 'profileModal');
    }
    public function update_password()
    {
        $userModel = new UserModel();
        $session = session();

        $userId = $session->get('user_id');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Get the current hashed password from DB
        $user = $userModel->find($userId);

        if (!$user || !password_verify($currentPassword, $user['userpassword'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.')->with('open_modal', 'editPasswordModal');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match.')->with('open_modal', 'editPasswordModal');
        }

        if (strlen($newPassword) < 8) {
            return redirect()->back()->with('error', 'New password must be at least 8 characters.')->with('open_modal', 'editPasswordModal');
        }

        // Check if the new password is the same as the current one
        if (password_verify($newPassword, $user['userpassword'])) {
            return redirect()->back()->with('error', 'New password cannot be the same as the old password.')->with('open_modal', 'editPasswordModal');
        }

        // Update password
        $userModel->update($userId, [
            'userpassword' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!')->with('open_modal', 'profileModal');
    }

}
