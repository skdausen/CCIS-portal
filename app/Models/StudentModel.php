<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'stb_id';
        protected $allowedFields    = [
        'student_id',
        'user_id',
        'email',
        'profimg',
        'lname',
        'fname',
        'mname',
        'sex',
        'address',
        'birthdate',
        'contactnum',
        'program_id',
        'year_level'
    ];
}
