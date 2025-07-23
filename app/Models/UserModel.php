<?php
// UserMModel.php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = [
        'username',
        'email',       
        'userpassword',
        'role',
        'status',
        'created_at',
        'last_login',
        'otp_code',
        'otp_expiry',
        'is_verified'
    ];

    protected $useTimestamps = false;

    protected $returnType = 'array';

    // Get user by username
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    // Check if a username already exists in the database
    public function usernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }

        public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function storeOTP($email, $otp, $expiry)
    {
        $user = $this->getByEmail($email);

        if ($user) {
            // Just update OTP fields
            return $this->where('email', $email)
                        ->set(['otp_code' => $otp, 'otp_expiry' => $expiry])
                        ->update();
        } else {
            // Insert new user if it doesn't exist
            return $this->insert([
                'email'      => $email,
                'otp_code'   => $otp,
                'otp_expiry' => $expiry,
                'is_verified' => 0
            ]);
        }
    }

    public function verifyOTP($email, $otp)
    {
        $user = $this->getByEmail($email);
        if ($user && $user['otp_code'] == $otp && strtotime($user['otp_expiry']) >= time()) {
            return true;
        }
        return false;
    }

    public function markVerified($email)
    {
        return $this->where('email', $email)->set('is_verified', 1)->update();
    }

    public function updatePassword($email, $hashedPassword)
    {
        return $this->where('email', $email)->set('userpassword', $hashedPassword)->update();
    }

    public function searchAndFilter($search = null, $role = null)
    {
        $builder = $this->builder('users');
        
        if ($search) {
            $builder->groupStart()
                ->like('username', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        if ($role) {
            $builder->where('role', $role);
        }

        return $builder;
    }

}
