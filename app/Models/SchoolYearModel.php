<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolYearModel extends Model
{
    protected $table = 'schoolyears';
    protected $primaryKey = 'schoolyear_id';
    protected $allowedFields = ['schoolyear'];
}
