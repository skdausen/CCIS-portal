<?php
// LoginModel.php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
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
        'profile_img',
        'lname',
        'fname',
        'mname',
        'sex',
        'address',
        'birthday',
        'otp_code',
        'otp_expiry',
        'is_verified',
        'contact_number'
    ];

    protected $returnType = 'array';

    // ✅ Get user by username
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    // ✅ Check if a username already exists in the database
    public function usernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }
}
