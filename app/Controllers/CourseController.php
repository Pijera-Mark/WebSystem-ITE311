<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CourseController extends BaseController
{
    public function browse()
    {
        $db = \Config\Database::connect();
        
        // Get all courses
        $courses = $db->table('courses')
            ->get()
            ->getResultArray();
        
        // Check if user is enrolled in each course
        $userId = session()->get('userID');
        if ($userId) {
            foreach ($courses as &$course) {
                $enrollment = $db->table('enrollments')
                    ->where('user_id', $userId)
                    ->where('course_id', $course['id'])
                    ->get()
                    ->getRow();
                
                $course['is_enrolled'] = $enrollment ? true : false;
            }
        }
        
        return view('courses/browse', ['courses' => $courses]);
    }
    
    public function view($id)
    {
        $db = \Config\Database::connect();
        
        // Get course details
        $course = $db->table('courses')
            ->where('id', $id)
            ->get()
            ->getRowArray();
        
        if (!$course) {
            session()->setFlashdata('error', 'Course not found');
            return redirect()->to('/courses/browse');
        }
        
        // Check if user is enrolled
        $userId = session()->get('userID');
        $course['is_enrolled'] = false;
        
        if ($userId) {
            $enrollment = $db->table('enrollments')
                ->where('user_id', $userId)
                ->where('course_id', $id)
                ->get()
                ->getRow();
            
            $course['is_enrolled'] = $enrollment ? true : false;
        }
        
        // Get course lessons
        $lessons = $db->table('lessons')
            ->where('course_id', $id)
            ->orderBy('order', 'ASC')
            ->get()
            ->getResultArray();
        
        // Get enrollment count
        $enrollmentCount = $db->table('enrollments')
            ->where('course_id', $id)
            ->countAllResults();
        
        return view('courses/view', [
            'course' => $course,
            'lessons' => $lessons,
            'enrollmentCount' => $enrollmentCount
        ]);
    }
}
