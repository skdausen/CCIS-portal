<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Password extends BaseController
{
    public function forgot()
    {
        // return view('password/forgot_form');
        return view('templates/header')
            . view('password/forgot_form')
            . view('templates/footer');
    }

    public function sendOTP()
    {
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
        $email = $this->request->getGet('email');
        // return view('password/verify_form', ['email' => $email]);

         return view('templates/header')
            . view('password/verify_form', ['email' => $email])
            . view('templates/footer');
    }

    public function verifyOTP()
    {
        $email = $this->request->getPost('email');
        $otp = $this->request->getPost('otp');

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
        $email = $this->request->getGet('email');

        // return view('password/reset_form', ['email' => $email]);

        return view('templates/header')
            . view('password/reset_form', ['email' => $email])
            . view('templates/footer');
    }

    public function resetPassword()
    {
        $email = $this->request->getPost('email');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $userModel = new UserModel();
        $userModel->updatePassword($email, $password);

        return redirect()->to('auth/login')->with('message', 'Password changed successfully!');
    }
}
