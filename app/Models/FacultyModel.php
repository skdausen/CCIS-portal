<?php

namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model
{
    protected $table            = 'faculty';
    protected $primaryKey       = 'ftb_id';
    protected $useAutoIncrement = true; // only if ftb_id is auto-increment
    protected $returnType       = 'array'; // you can use 'object' too
    protected $allowedFields    = ['faculty_id', 'user_id'];
}

