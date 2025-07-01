<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model

{
    protected $table = 'users';
    protected $allowedFields = ['email', 'otp', 'otp_expiry'];

    public function deleteByEmail($email)
    {
        return $this->where('email', $email)->delete();
    }

    public function insertOtp($email, $otp, $expiry)
    {
        return $this->insert([
            'email' => $email,
            'otp' => $otp,
            'otp_expiry' => $expiry
        ]);
    }
}