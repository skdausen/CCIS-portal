<?php

namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $table = 'grades';
    protected $primaryKey = 'grade_id';

    protected $allowedFields = [
        'stb_id', 'class_id', 'mt_grade', 'fn_grade', 'sem_grade'
    ];

    protected $useTimestamps = false;
}
