<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model
{
    protected $table            = 'faculty';
    protected $primaryKey       = 'ftb_id';
    protected $useAutoIncrement = true; // ✅ Only if `ftb_id` is auto-increment in DB
    protected $returnType       = 'array'; // ✅ or use 'object' if preferred
    protected $allowedFields    = [
        'faculty_id',
        'user_id',
        'profimg',
        'lname',
        'fname',
        'mname',
        'sex',
        'address',
        'birthdate',
        'contactnum',
        'employee_status',
    ];
}

