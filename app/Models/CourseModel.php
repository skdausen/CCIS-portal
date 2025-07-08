<?php
namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'course'; // Make sure this matches your database
    protected $primaryKey = 'course_id';
    protected $allowedFields = ['course_code', 'course_description', 'lec_units', 'lab_units'];
}
