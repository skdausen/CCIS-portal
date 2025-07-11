<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
        protected $allowedFields    = [
        'student_id',
        'user_id',
        'email',
        'student_profimg',
        'student_lname',
        'student_fname',
        'student_mname',
        'student_sex',
        'student_address',
        'student_birthdate',
        'student_contactnum'
    ];
}
