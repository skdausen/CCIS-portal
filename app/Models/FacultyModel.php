<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model
{
    protected $table = 'faculty';
    protected $primaryKey = 'faculty_id';
    protected $allowedFields = ['user_id', 'faculty_id', 'employment_status'];
}
