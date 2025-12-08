<?php

namespace App\Controllers\Instructor;

use App\Controllers\BaseController;

class InstructorController extends BaseController
{
    public function createCourse()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[5]|max_length[255]',
                'description' => 'required|min_length[10]',
                'credits' => 'required|integer|greater_than[0]',
                'duration' => 'required|integer|greater_than[0]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'credits' => $this->request->getPost('credits'),
                    'duration' => $this->request->getPost('duration'),
                    'instructor_id' => session()->get('userID'),
                    'status' => 'draft',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $db = \Config\Database::connect();
                $db->table('courses')->insert($data);
                
                session()->setFlashdata('success', 'Course created successfully');
                return redirect()->to('/instructor/my-courses');
            } else {
                return redirect()->back()->with('validation', $this->validator);
            }
        }
        
        return view('instructor/create-course');
    }
    
    public function myCourses()
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Get instructor's courses
        $courses = $db->table('courses')
            ->where('instructor_id', $instructorId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
        
        // Get statistics for each course
        foreach ($courses as &$course) {
            $course['studentCount'] = $db->table('enrollments')
                ->where('course_id', $course['id'])
                ->countAllResults();
            
            $course['lessonCount'] = $db->table('lessons')
                ->where('course_id', $course['id'])
                ->countAllResults();
            
            $course['quizCount'] = $db->table('quizzes')
                ->join('lessons', 'lessons.id = quizzes.lesson_id')
                ->where('lessons.course_id', $course['id'])
                ->countAllResults();
        }
        
        return view('instructor/my-courses', [
            'courses' => $courses,
            'pageTitle' => 'My Courses'
        ]);
    }
    
    public function viewCourse($id)
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Verify course belongs to instructor
        $course = $db->table('courses')
            ->where('id', $id)
            ->where('instructor_id', $instructorId)
            ->get()
            ->getRowArray();
        
        if (!$course) {
            session()->setFlashdata('error', 'Course not found or access denied');
            return redirect()->to('/instructor/my-courses');
        }
        
        // Get course lessons
        $lessons = $db->table('lessons')
            ->where('course_id', $id)
            ->orderBy('position', 'ASC')
            ->get()
            ->getResultArray();
        
        // Get enrolled students
        $students = $db->table('enrollments')
            ->join('users', 'users.id = enrollments.user_id')
            ->where('enrollments.course_id', $id)
            ->select('enrollments.*, users.name, users.email')
            ->get()
            ->getResultArray();
        
        return view('instructor/view-course', [
            'course' => $course,
            'lessons' => $lessons,
            'students' => $students,
            'pageTitle' => $course['title']
        ]);
    }
    
    public function editCourse($id)
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Verify course belongs to instructor
        $course = $db->table('courses')
            ->where('id', $id)
            ->where('instructor_id', $instructorId)
            ->get()
            ->getRowArray();
        
        if (!$course) {
            session()->setFlashdata('error', 'Course not found or access denied');
            return redirect()->to('/instructor/my-courses');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[5]|max_length[255]',
                'description' => 'required|min_length[10]',
                'credits' => 'required|integer|greater_than[0]',
                'duration' => 'required|integer|greater_than[0]',
                'status' => 'required|in_list[draft,published,archived]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'credits' => $this->request->getPost('credits'),
                    'duration' => $this->request->getPost('duration'),
                    'status' => $this->request->getPost('status'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $db->table('courses')->where('id', $id)->update($data);
                
                session()->setFlashdata('success', 'Course updated successfully');
                return redirect()->to('/instructor/course/' . $id);
            } else {
                return redirect()->back()->with('validation', $this->validator);
            }
        }
        
        return view('instructor/edit-course', [
            'course' => $course,
            'pageTitle' => 'Edit Course'
        ]);
    }
    
    public function courseAnalytics($id)
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Verify course belongs to instructor
        $course = $db->table('courses')
            ->where('id', $id)
            ->where('instructor_id', $instructorId)
            ->get()
            ->getRowArray();
        
        if (!$course) {
            session()->setFlashdata('error', 'Course not found or access denied');
            return redirect()->to('/instructor/my-courses');
        }
        
        // Get enrollment statistics
        $totalStudents = $db->table('enrollments')
            ->where('course_id', $id)
            ->countAllResults();
        
        // Get lesson completion stats
        $lessonStats = $db->table('lessons')
            ->where('course_id', $id)
            ->get()
            ->getResultArray();
        
        // Get quiz submission stats
        $quizStats = $db->table('quizzes')
            ->join('lessons', 'lessons.id = quizzes.lesson_id')
            ->join('submissions', 'submissions.quiz_id = quizzes.id', 'left')
            ->where('lessons.course_id', $id)
            ->select('quizzes.*, COUNT(submissions.id) as submission_count, AVG(submissions.score) as avg_score')
            ->groupBy('quizzes.id')
            ->get()
            ->getResultArray();
        
        return view('instructor/analytics', [
            'course' => $course,
            'totalStudents' => $totalStudents,
            'lessonStats' => $lessonStats,
            'quizStats' => $quizStats,
            'pageTitle' => 'Course Analytics'
        ]);
    }
    
    public function gradeSubmissions()
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Get courses by this instructor
        $courseIds = $db->table('courses')
            ->where('instructor_id', $instructorId)
            ->select('id')
            ->get()
            ->getResultArray();
        
        $courseIds = array_column($courseIds, 'id');
        
        if (empty($courseIds)) {
            return view('instructor/grade-submissions', [
                'submissions' => [],
                'pageTitle' => 'Grade Submissions'
            ]);
        }
        
        // Get pending quiz submissions
        $submissions = $db->table('submissions')
            ->join('quizzes', 'quizzes.id = submissions.quiz_id')
            ->join('lessons', 'lessons.id = quizzes.lesson_id')
            ->join('courses', 'courses.id = lessons.course_id')
            ->join('users', 'users.id = submissions.student_id')
            ->whereIn('courses.id', $courseIds)
            ->where('submissions.score', null)
            ->select('submissions.*, quizzes.title as quiz_title, courses.title as course_title, users.name as student_name')
            ->orderBy('submissions.submitted_at', 'ASC')
            ->get()
            ->getResultArray();
        
        return view('instructor/grade-submissions', [
            'submissions' => $submissions,
            'pageTitle' => 'Grade Submissions'
        ]);
    }
    
    public function manageStudents()
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Get all students enrolled in instructor's courses
        $students = $db->table('enrollments')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('courses.instructor_id', $instructorId)
            ->select('enrollments.*, users.name, users.email, courses.title as course_title')
            ->orderBy('users.name', 'ASC')
            ->get()
            ->getResultArray();
        
        return view('instructor/manage-students', [
            'students' => $students,
            'pageTitle' => 'Manage Students'
        ]);
    }
    
    public function reports()
    {
        $db = \Config\Database::connect();
        $instructorId = session()->get('userID');
        
        // Get instructor statistics
        $stats = [
            'totalCourses' => $db->table('courses')->where('instructor_id', $instructorId)->countAllResults(),
            'totalStudents' => $db->table('enrollments')
                ->join('courses', 'courses.id = enrollments.course_id')
                ->where('courses.instructor_id', $instructorId)
                ->countAllResults(),
            'totalLessons' => $db->table('lessons')
                ->join('courses', 'courses.id = lessons.course_id')
                ->where('courses.instructor_id', $instructorId)
                ->countAllResults(),
            'totalQuizzes' => $db->table('quizzes')
                ->join('lessons', 'lessons.id = quizzes.lesson_id')
                ->join('courses', 'courses.id = lessons.course_id')
                ->where('courses.instructor_id', $instructorId)
                ->countAllResults()
        ];
        
        // Get course performance
        $coursePerformance = $db->table('courses')
            ->where('instructor_id', $instructorId)
            ->get()
            ->getResultArray();
        
        foreach ($coursePerformance as &$course) {
            $course['enrollmentCount'] = $db->table('enrollments')
                ->where('course_id', $course['id'])
                ->countAllResults();
            
            $course['avgCompletion'] = $this->calculateCourseCompletion($course['id']);
        }
        
        return view('instructor/reports', [
            'stats' => $stats,
            'coursePerformance' => $coursePerformance,
            'pageTitle' => 'Instructor Reports'
        ]);
    }
    
    private function calculateCourseCompletion($courseId)
    {
        $db = \Config\Database::connect();
        
        // Get total lessons for course
        $totalLessons = $db->table('lessons')
            ->where('course_id', $courseId)
            ->countAllResults();
        
        if ($totalLessons == 0) return 0;
        
        // Get total completed lessons across all students
        $completedLessons = $db->table('lesson_progress')
            ->join('enrollments', 'enrollments.user_id = lesson_progress.student_id')
            ->where('enrollments.course_id', $courseId)
            ->where('lesson_progress.completed', 1)
            ->countAllResults();
        
        // Get total enrolled students
        $totalStudents = $db->table('enrollments')
            ->where('course_id', $courseId)
            ->countAllResults();
        
        if ($totalStudents == 0) return 0;
        
        return round(($completedLessons / ($totalLessons * $totalStudents)) * 100, 2);
    }
}
