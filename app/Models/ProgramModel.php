<?php
namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'program_id';
    protected $allowedFields = ['program_name'];
    protected $returnType = 'array';
}
