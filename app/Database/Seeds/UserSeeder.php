<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'student1',
                'email'    => 'student1@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'student',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'instructor1',
                'email'    => 'instructor1@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role'     => 'instructor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'admin1',
                'email'    => 'admin1@example.com',
                'password' => password_hash('adminpass', PASSWORD_DEFAULT),
                'role'     => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert batch data into the 'users' table
        $this->db->table('users')->insertBatch($data);
    }
}
