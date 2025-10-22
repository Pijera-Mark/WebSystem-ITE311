<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Demo student
        $studentPasswordHash = password_hash('Secret123!', PASSWORD_DEFAULT);
        $student = [
            'name' => 'Demo Student',
            'email' => 'demo.student@example.com',
            'password_hash' => $studentPasswordHash,
            'role' => 'student',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $studentExists = $this->db->table('users')->where('email', $student['email'])->get()->getRowArray();
        if (! $studentExists) {
            $this->db->table('users')->insert($student);
        }

        // Admin user
        $adminPasswordHash = password_hash('admin', PASSWORD_DEFAULT);
        $admin = [
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password_hash' => $adminPasswordHash,
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $users = $this->db->table('users');
        $adminExists = $users->where('email', 'admin@gmail.com')->get()->getRowArray();
        if (! $adminExists) {
            // If an old admin exists with any previous admin email, update it
            $oldAdmin = $users->where('role', 'admin')->get()->getRowArray();
            if ($oldAdmin) {
                $users->where('id', $oldAdmin['id'])->update([
                    'email' => 'admin@gmail.com',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $users->insert($admin);
            }
        }
    }
}
