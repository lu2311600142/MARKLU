<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the Student Portal',
                'content' => 'We are excited to launch our new online student portal.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Midterm Examination Schedule',
                'content' => 'Midterm exams will be held from October 15-20, 2025.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}