<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get instructor ID
        $instructor = $this->db->table('users')->where('email', 'sarah.johnson@lms.com')->get()->getRowArray();
        $instructorId = $instructor ? $instructor['id'] : 1;

        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of HTML, CSS, and JavaScript to build modern websites.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Database Management Systems',
                'description' => 'Comprehensive course on database design, SQL, and database administration.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'PHP Programming',
                'description' => 'Master PHP programming from basics to advanced concepts including frameworks.',
                'instructor_id' => $instructorId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($courses as $course) {
            $exists = $this->db->table('courses')->where('title', $course['title'])->get()->getRowArray();
            if (! $exists) {
                $this->db->table('courses')->insert($course);
            }
        }
    }
}
