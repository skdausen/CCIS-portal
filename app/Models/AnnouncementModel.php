<?php
// AnnouncementModel.php 
namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements'; // Your table name
    protected $primaryKey = 'announcement_id';

    protected $allowedFields = [
        'title',
        'content',
        'created_by',
        'audience',
        'created_at',
        'event_datetime' // ✅ THIS IS NEEDED TO SAVE THE EVENT DATE
    ];


    protected $useTimestamps = false; // true if using created_at automatically

    public function getAllWithUsernames()
    {
        return $this->db->table('announcements')
            ->select('announcements.*, users.username')
            ->join('users', 'users.user_id = announcements.created_by', 'left')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }


}
