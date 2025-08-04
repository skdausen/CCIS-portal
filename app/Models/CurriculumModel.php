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

public function getFilteredPaginatedCurriculums($search = null, $perPage = 10)
{
    // Start building a query
    $builder = $this->select('curriculums.curriculum_id, curriculums.curriculum_name, curriculums.program_id, programs.program_name')
                    ->join('programs', 'programs.program_id = curriculums.program_id');

    // If there is a search term, add filtering
    if (!empty($search)) {
        $builder->groupStart()
                ->like('curriculums.curriculum_name', $search)
                ->orLike('programs.program_name', $search)
                ->groupEnd();
    }

    // Return paginated result
    return $builder->orderBy('curriculums.curriculum_name', 'ASC')
                   ->paginate($perPage, 'curriculums');
}

}