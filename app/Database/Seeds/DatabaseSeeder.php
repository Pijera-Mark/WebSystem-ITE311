<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear all tables in reverse order of foreign key dependencies
        // Only clear tables that exist
        $tables = ['enrollments', 'quizzes', 'lessons', 'courses', 'remember_tokens', 'users'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->emptyTable();
            }
        }
        
        // Run seeders in the correct order to respect foreign key constraints
        $this->call('UserSeeder');
        $this->call('CourseSeeder');
        $this->call('LessonSeeder');
        $this->call('QuizSeeder');
        $this->call('EnrollmentSeeder');
    }
}
