<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Get lesson IDs for quizzes
        $htmlLesson = $this->db->table('lessons')->where('title', 'HTML Fundamentals')->get()->getRowArray();
        $cssLesson = $this->db->table('lessons')->where('title', 'CSS Styling')->get()->getRowArray();
        $jsLesson = $this->db->table('lessons')->where('title', 'JavaScript Basics')->get()->getRowArray();
        $sqlLesson = $this->db->table('lessons')->where('title', 'SQL Fundamentals')->get()->getRowArray();

        $quizzes = [];

        // HTML Quiz
        if ($htmlLesson) {
            $quizzes = array_merge($quizzes, [
                [
                    'lesson_id' => $htmlLesson['id'],
                    'question' => 'What does HTML stand for?',
                    'options' => json_encode(['Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language']),
                    'correct_answer' => 'Hyper Text Markup Language',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'lesson_id' => $htmlLesson['id'],
                    'question' => 'Which tag is used for the largest heading?',
                    'options' => json_encode(['<h6>', '<heading>', '<h1>', '<h>']),
                    'correct_answer' => '<h1>',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        // CSS Quiz
        if ($cssLesson) {
            $quizzes = array_merge($quizzes, [
                [
                    'lesson_id' => $cssLesson['id'],
                    'question' => 'What does CSS stand for?',
                    'options' => json_encode(['Computer Style Sheets', 'Cascading Style Sheets', 'Creative Style Sheets', 'Colorful Style Sheets']),
                    'correct_answer' => 'Cascading Style Sheets',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'lesson_id' => $cssLesson['id'],
                    'question' => 'Which property is used to change the background color?',
                    'options' => json_encode(['bgcolor', 'color', 'background-color', 'background']),
                    'correct_answer' => 'background-color',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        // JavaScript Quiz
        if ($jsLesson) {
            $quizzes = array_merge($quizzes, [
                [
                    'lesson_id' => $jsLesson['id'],
                    'question' => 'What is the correct way to write a JavaScript array?',
                    'options' => json_encode(['var colors = "red", "green", "blue"', 'var colors = ["red", "green", "blue"]', 'var colors = (1:"red", 2:"green", 3:"blue")', 'var colors = 1 = ("red"), 2 = ("green"), 3 = ("blue")']),
                    'correct_answer' => 'var colors = ["red", "green", "blue"]',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        // SQL Quiz
        if ($sqlLesson) {
            $quizzes = array_merge($quizzes, [
                [
                    'lesson_id' => $sqlLesson['id'],
                    'question' => 'Which SQL statement is used to extract data from a database?',
                    'options' => json_encode(['GET', 'OPEN', 'EXTRACT', 'SELECT']),
                    'correct_answer' => 'SELECT',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'lesson_id' => $sqlLesson['id'],
                    'question' => 'Which SQL statement is used to update data in a database?',
                    'options' => json_encode(['UPDATE', 'SAVE', 'MODIFY', 'CHANGE']),
                    'correct_answer' => 'UPDATE',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        foreach ($quizzes as $quiz) {
            $exists = $this->db->table('quizzes')
                ->where('lesson_id', $quiz['lesson_id'])
                ->where('question', $quiz['question'])
                ->get()->getRowArray();
            if (! $exists) {
                $this->db->table('quizzes')->insert($quiz);
            }
        }
    }
}
