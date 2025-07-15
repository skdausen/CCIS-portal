<?php
namespace App\Models;

use CodeIgniter\Model;

class CurriculumModel extends Model
{
    protected $table = 'curriculums';
    protected $primaryKey = 'curriculum_id';
    protected $allowedFields = ['curriculum_name', 'program_id'];

public function getCurriculumsWithProgramName()
{
    return $this->db->table('curriculums AS c')
        ->select('c.curriculum_id, c.curriculum_name, c.program_id, p.program_name') // <-- added c.program_id
        ->join('programs AS p', 'p.program_id = c.program_id')
        ->orderBy('c.curriculum_name', 'ASC')
        ->get()
        ->getResultArray();
}
}