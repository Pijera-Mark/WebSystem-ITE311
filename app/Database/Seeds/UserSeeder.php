<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users
        $this->db->table('users')->emptyTable();
        
        // Admin user
        $adminPasswordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $admin = [
            'name' => 'System Administrator',
            'email' => 'admin@lms.com',
            'password_hash' => $adminPasswordHash,
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($admin);

        // Instructor user
        $instructorPasswordHash = password_hash('instructor123', PASSWORD_DEFAULT);
        $instructor = [
            'name' => 'Instructor',
            'email' => 'instructor@lms.com',
            'password_hash' => $instructorPasswordHash,
            'role' => 'instructor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($instructor);

        // Student user
        $studentPasswordHash = password_hash('student123', PASSWORD_DEFAULT);
        $student = [
            'name' => 'Student',
            'email' => 'student@lms.com',
            'password_hash' => $studentPasswordHash,
            'role' => 'student',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($student);
    }
}
