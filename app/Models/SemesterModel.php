<?php

// app/Models/SemesterModel.php
namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';
    protected $allowedFields = ['semester', 'schoolyear_id'];
}
