<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'users'; // Change this to your actual table name
    protected $primaryKey = 'id';

    protected $allowedFields = ['username', 'password']; // Add more fields if needed

    protected $returnType = 'array';

    /**
     * Get user by username
     *
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername($username)
    {
        return $this->where(['username' => $username])->first();
    }
}
