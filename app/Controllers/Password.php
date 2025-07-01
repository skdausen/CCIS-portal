<?php

namespace App\Controllers;
use App\Models\OtpModel;

// use CodeIgniter\Exceptions\PageNotFoundException;

class Password extends BaseController
{
    // create a method to display the HTML form you have created
    public function forgotPasswordForm()
    {
        // We load the Form helper with the helper() function. Most helper functions require the helper to be loaded before use.
        helper('form');

        // Then it returns the created form view.
        return view('templates/header', ['title' => 'Forgot Password'])
            . view('password/forgotPasswordForm')
            . view('templates/footer');
    }

    public function sendOtp()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $otp = rand(100000, 999999); // Generate 6-digit OTP
            $otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            $OtpModel = new OtpModel();

            // Clear any existing OTP for this email
            $OtpModel->deleteByEmail($email);

            // Insert new OTP
            $saved = $OtpModel->insertOtp($email, $otp, $otp_expiry);

            // $sql = "INSERT INTO users (email, otp_code, otp_expiry) VALUES (?, ?, ?)";
            // $stmt = $conn->prepare($sql);
            // $stmt->bind_param("sss", $email, $otp, $otp_expiry);
            
            if ($saved) {
                helper('email'); // Or load custom email library
                sendOtpEmail($email, $otp); // From helper or library

                return redirect()->to('/verify?email=' . urlencode($email));
            } else {
                return redirect()->back()->with('error', 'Failed to store OTP');
            }

            // if ($stmt->execute()) {
            //     // Debug output
            //     echo "OTP stored in database: $otp (expires: $otp_expiry)<br>";
                
            //     // Send email
            //     sendOtpEmail($email, $otp);
                
            //     // Redirect to verify page
            //     header("Location: verify.php?email=" . urlencode($email));
            //     exit();
            // } else {
            //     echo "Error: " . $stmt->error;
            // }
        }

        return view('password/forgot'); // Your form view

    }
}