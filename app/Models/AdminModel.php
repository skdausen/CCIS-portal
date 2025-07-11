<?php
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'atb_id';
        protected $allowedFields    = [
        'admin_id',
        'user_id',
        'email',
        'admin_profimg',
        'admin_lname',
        'admin_fname',
        'admin_mname',
        'admin_sex',
        'admin_address',
        'admin_birthdate',
        'admin_contactnum'
    ];
    protected $useTimestamps = true; // Enable timestamps if your table has created_at and updated_at fields

    public function getAdminById($adminId)
    {
        return $this->where('admin_id', $adminId)->first();
    }

    public function getAllAdmins()
    {
        return $this->findAll();
    }

    public function createAdmin($data)
    {
        return $this->insert($data);
    }

    public function updateAdmin($adminId, $data)
    {
        return $this->update($adminId, $data);
    }

    public function deleteAdmin($adminId)
    {
        return $this->delete($adminId);
    }    
}
