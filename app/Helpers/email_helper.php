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
        $mail->Body    = "
                <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                    <h2 style='color: #004080;'>CCIS Password Reset Request</h2>

                    <p>Hi <strong>$email</strong>,</p>

                    <p>We received a request to reset the password for your CCIS account. To proceed, please use the one-time verification code below:</p>

                    <div style='text-align: center; margin: 30px 0;'>
                        <p style='font-size: 18px; margin-bottom: 5px;'>Your verification code is:</p>
                        <p style='font-size: 32px; font-weight: bold; color: #004080;'>$otp</p>
                        <p style='color: #888;'>This code will expire in 5 minutes.</p>
                    </div>

                    <p>To verify your identity and continue with the password reset process, please click the button below:</p>

                    <div style='text-align: center; margin: 20px 0;'>
                        <a href='" . base_url("password/verify?email=" . urlencode($email)) . "' style='background-color: #004080; color: #fff; text-decoration: none; padding: 12px 20px; border-radius: 6px; display: inline-block;'>
                        Verify Account
                        </a>
                    </div>

                    <p>If you did not request this code, you can safely ignore this message. We’ll never ask for your code outside of official CCIS platforms.</p>

                    <p>Thank you,<br><strong>The CCIS Account Team</strong></p>
                </div>
            ";

        $mail->send();
    } catch (Exception $e) {
        log_message('error', 'Email error: ' . $mail->ErrorInfo);
    }
}
