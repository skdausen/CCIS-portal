<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpEmail($email, $otp)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ccisportal2025@gmail.com';
        $mail->Password   = 'oqwt yykv msqb xhht';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('ccisportal2025@gmail.com', 'OTP Verification');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: <b>$otp</b><br><br>
                          The code will expire in 5 minutes.<br>
                          <a href='".base_url("password/verify?email=" . urlencode($email))."'>Click here to verify</a>";

        $mail->send();
    } catch (Exception $e) {
        log_message('error', 'Email error: ' . $mail->ErrorInfo);
    }
}