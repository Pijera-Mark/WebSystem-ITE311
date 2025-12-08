<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        // Get student and course IDs
        $students = $this->db->table('users')->where('role', 'student')->get()->getResultArray();
        $courses = $this->db->table('courses')->get()->getResultArray();

        $enrollments = [];

        // Enroll students in courses
        foreach ($students as $student) {
            foreach ($courses as $course) {
                // Create some variety in enrollment status
                $statuses = ['active', 'active', 'active', 'completed']; // More active than completed
                $status = $statuses[array_rand($statuses)];

                $enrollment = [
                    'user_id' => $student['id'],
                    'course_id' => $course['id'],
                    'status' => $status,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                // Check if enrollment already exists
                $exists = $this->db->table('enrollments')
                    ->where('user_id', $enrollment['user_id'])
                    ->where('course_id', $enrollment['course_id'])
                    ->get()->getRowArray();

                if (! $exists) {
                    $enrollments[] = $enrollment;
                }
            }
        }

        // Insert enrollments in batches
        if (! empty($enrollments)) {
            $this->db->table('enrollments')->insertBatch($enrollments);
        }
    }
}
