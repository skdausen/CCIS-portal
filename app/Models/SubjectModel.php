<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    protected $allowedFields = [
        'subject_code',
        'subject_name',
        'subject_type',
        'lec_units',
        'lab_units',
        'total_units',
        'curriculum_id',
        'yearlevel_sem', 
    ];
}
