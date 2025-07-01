<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id'; // 👈 matches your DB
    protected $allowedFields = ['username', 'userpassword', 'role', 'created_at', 'last_login'];
    protected $returnType = 'array';

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
