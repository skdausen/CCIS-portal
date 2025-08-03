<?php

namespace App\Controllers;

use App\Models\UserModel;

class Password extends BaseController
{
    public function forgot()
    {
        $model    = new UserModel();
        $username = session()->get('username');
        $users = $model->where('username', $username)->first();
        if (session()->get('isLoggedIn')) {
            if ($users['role'] === 'student') {
                return redirect()->to('student/home');
            } elseif ($users['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } elseif (in_array($users['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            }
        }
        // return view('password/forgot_form');
        return view('templates/login_header')
            . view('password/forgot_form')
            . view('templates/login_footer');
    }

    public function sendOTP()
    {
        $model    = new UserModel();
        $username = session()->get('username');
        $users = $model->where('username', $username)->first();
        if (session()->get('isLoggedIn')) {
            if ($users['role'] === 'student') {
                return redirect()->to('student/home');
            } elseif ($users['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } elseif (in_array($users['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            }
        }

        helper('email');

        $email = $this->request->getPost('email');

        $userModel = new UserModel();
        $user = $userModel->getByEmail($email);

        // Check if email exists in the database
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email not found.');
        }

        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        
        $userModel->storeOTP($email, $otp, $expiry);

        sendOtpEmail($email, $otp);

        return redirect()->to('/password/verify?email=' . urlencode($email));
    }

    public function verifyForm()
    {
        $model    = new UserModel();
        $username = session()->get('username');
        $users = $model->where('username', $username)->first();
        if (session()->get('isLoggedIn')) {
            if ($users['role'] === 'student') {
                return redirect()->to('student/home');
            } elseif ($users['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } elseif (in_array($users['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            }
        }
        $email = $this->request->getGet('email');
        // return view('password/verify_form', ['email' => $email]);

        return view('templates/login_header')
            . view('password/verify_form', ['email' => $email])
            . view('templates/login_footer');
    }

    public function verifyOTP()
    {
        $email = $this->request->getPost('email');
        $otp = $this->request->getPost('otp');
        $otp = preg_replace('/\s+/', '', $otp);
        if (!preg_match('/^\d{6}$/', $otp)) {
            return redirect()->back()->with('error', 'OTP code must be exactly 6 digits.');
        }

        $userModel = new UserModel();
        if ($userModel->verifyOTP($email, $otp)) {
            $userModel->markVerified($email);
            return redirect()->to('/password/reset?email=' . urlencode($email));
        } else {
            return redirect()->back()->with('error', 'Invalid or expired OTP');
        }
    }

    public function resetForm()
    {
        $model    = new UserModel();
        $username = session()->get('username');
        $users = $model->where('username', $username)->first();
        if (session()->get('isLoggedIn')) {
            if ($users['role'] === 'student') {
                return redirect()->to('student/home');
            } elseif ($users['role'] === 'faculty') {
                return redirect()->to('faculty/home');
            } elseif (in_array($users['role'], ['admin', 'superadmin'])) {
                return redirect()->to('admin/home');
            }
        }
        $email = $this->request->getGet('email');

        return view('templates/login_header')
            . view('password/reset_form', ['email' => $email])
            . view('templates/login_footer');
    }

    public function resetPassword()
    {
        $email = $this->request->getPost('email');
        $newPasswordRaw = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Check if the new password is the same as the current one
        if (password_verify($newPasswordRaw, $user['userpassword'])) {
            session()->setFlashdata('error', 'New password cannot be the same as the old password.');
            return redirect()->back();
        }

        $password = password_hash($newPasswordRaw, PASSWORD_DEFAULT);
        $userModel->updatePassword($email, $password);

        session()->setFlashdata('success', 'Your password has been reset successfully');
        return redirect()->to('/auth/login');
    }
}
