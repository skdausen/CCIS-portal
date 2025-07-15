<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'atb_id';
        protected $allowedFields    = [
        'admin_id',
        'user_id',
        'email',
        'profimg',
        'lname',
        'fname',
        'mname',
        'sex',
        'address',
        'birthdate',
        'contactnum'
    ];

}
