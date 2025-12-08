<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // Get course IDs
        $webDevCourse = $this->db->table('courses')->where('title', 'Introduction to Web Development')->get()->getRowArray();
        $dbCourse = $this->db->table('courses')->where('title', 'Database Management Systems')->get()->getRowArray();
        $phpCourse = $this->db->table('courses')->where('title', 'PHP Programming')->get()->getRowArray();

        $lessons = [];

        // Web Development Lessons
        if ($webDevCourse) {
            $lessons = array_merge($lessons, [
                [
                    'course_id' => $webDevCourse['id'],
                    'title' => 'HTML Fundamentals',
                    'content' => 'Learn the basic structure of HTML documents, common tags, and semantic markup.',
                    'position' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'course_id' => $webDevCourse['id'],
                    'title' => 'CSS Styling',
                    'content' => 'Master CSS selectors, properties, and layout techniques including Flexbox and Grid.',
                    'position' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'course_id' => $webDevCourse['id'],
                    'title' => 'JavaScript Basics',
                    'content' => 'Introduction to JavaScript programming, variables, functions, and DOM manipulation.',
                    'position' => 3,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        // Database Lessons
        if ($dbCourse) {
            $lessons = array_merge($lessons, [
                [
                    'course_id' => $dbCourse['id'],
                    'title' => 'Database Design Principles',
                    'content' => 'Understanding normalization, relationships, and entity-relationship diagrams.',
                    'position' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'course_id' => $dbCourse['id'],
                    'title' => 'SQL Fundamentals',
                    'content' => 'Writing SELECT, INSERT, UPDATE, DELETE queries and understanding joins.',
                    'position' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        // PHP Lessons
        if ($phpCourse) {
            $lessons = array_merge($lessons, [
                [
                    'course_id' => $phpCourse['id'],
                    'title' => 'PHP Syntax and Variables',
                    'content' => 'Basic PHP syntax, variables, data types, and operators.',
                    'position' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'course_id' => $phpCourse['id'],
                    'title' => 'PHP Functions and Arrays',
                    'content' => 'Creating and using functions, working with arrays, and built-in PHP functions.',
                    'position' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        foreach ($lessons as $lesson) {
            $exists = $this->db->table('lessons')
                ->where('course_id', $lesson['course_id'])
                ->where('title', $lesson['title'])
                ->get()->getRowArray();
            if (! $exists) {
                $this->db->table('lessons')->insert($lesson);
            }
        }
    }
}
