<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // The name of your users table
    protected $primaryKey = 'user_id'; // Your primary key, change if needed

    protected $allowedFields = [
        'username',
        'userpassword',
        'role',
        'created_at',
        'last_login',
        // any other fields you have
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}