<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'class';
    protected $primaryKey = 'class_id';
    protected $allowedFields = ['faculty_id', 'course_id', 'class_day', 'class_start', 'class_end', 'class_room'];
}
