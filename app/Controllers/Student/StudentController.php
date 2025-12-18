<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;

class StudentController extends BaseController
{
    public function dashboard()
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Get enrolled courses
        $enrolledCourses = $db->table('enrollments')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id')
            ->where('enrollments.user_id', $studentId)
            ->select('enrollments.*, courses.*, users.name as instructor_name')
            ->get()
            ->getResultArray();
        
        // Calculate progress for each course
        foreach ($enrolledCourses as &$course) {
            $totalLessons = $db->table('lessons')
                ->where('course_id', $course['course_id'])
                ->countAllResults();
            
            $completedLessons = $db->table('lesson_progress')
                ->where('student_id', $studentId)
                ->where('course_id', $course['course_id'])
                ->where('completed', 1)
                ->countAllResults();
            
            $course['progress'] = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
            $course['total_lessons'] = $totalLessons;
            $course['completed_lessons'] = $completedLessons;
        }
        
        // Get student statistics
        $stats = [
            'enrolledCourses' => count($enrolledCourses),
            'completedCourses' => $db->table('enrollments')
                ->where('user_id', $studentId)
                ->where('status', 'completed')
                ->countAllResults(),
            'totalQuizzes' => $db->table('submissions')
                ->where('student_id', $studentId)
                ->countAllResults(),
            'avgScore' => $db->table('submissions')
                ->where('student_id', $studentId)
                ->selectAvg('score')
                ->get()
                ->getRowArray()['score'] ?? 0
        ];
        
        return view('student/dashboard', [
            'enrolledCourses' => $enrolledCourses,
            'stats' => $stats,
            'pageTitle' => 'Student Dashboard'
        ]);
    }
    
    public function myCourses()
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Get enrolled courses with progress
        $courses = $db->table('enrollments')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id')
            ->where('enrollments.user_id', $studentId)
            ->select('enrollments.*, courses.*, users.name as instructor_name')
            ->get()
            ->getResultArray();
        
        foreach ($courses as &$course) {
            $course['progress'] = $this->getCourseProgress($studentId, $course['course_id']);
            $course['lessons_count'] = $db->table('lessons')
                ->where('course_id', $course['course_id'])
                ->countAllResults();
        }
        
        return view('student/my-courses', [
            'courses' => $courses,
            'pageTitle' => 'My Courses'
        ]);
    }
    
    public function viewCourse($id)
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Verify student is enrolled
        $enrollment = $db->table('enrollments')
            ->where('user_id', $studentId)
            ->where('course_id', $id)
            ->get()
            ->getRowArray();
        
        if (!$enrollment) {
            session()->setFlashdata('error', 'You are not enrolled in this course');
            return redirect()->to('/student/my-courses');
        }
        
        // Get course details
        $course = $db->table('courses')
            ->join('users', 'users.id = courses.instructor_id')
            ->where('courses.id', $id)
            ->select('courses.*, users.name as instructor_name')
            ->get()
            ->getRowArray();
        
        // Get lessons with progress
        $lessons = $db->table('lessons')
            ->where('course_id', $id)
            ->orderBy('position', 'ASC')
            ->get()
            ->getResultArray();
        
        foreach ($lessons as &$lesson) {
            $progress = $db->table('lesson_progress')
                ->where('student_id', $studentId)
                ->where('lesson_id', $lesson['id'])
                ->get()
                ->getRowArray();
            
            $lesson['completed'] = $progress ? $progress['completed'] : 0;
            $lesson['progress_percent'] = $progress ? $progress['progress_percent'] : 0;
        }
        
        return view('student/view-course', [
            'course' => $course,
            'lessons' => $lessons,
            'enrollment' => $enrollment,
            'pageTitle' => $course['title']
        ]);
    }
    
    public function courseProgress($id)
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Verify enrollment
        $enrollment = $db->table('enrollments')
            ->where('user_id', $studentId)
            ->where('course_id', $id)
            ->get()
            ->getRowArray();
        
        if (!$enrollment) {
            session()->setFlashdata('error', 'You are not enrolled in this course');
            return redirect()->to('/student/my-courses');
        }
        
        // Get detailed progress
        $course = $db->table('courses')->where('id', $id)->get()->getRowArray();
        $lessons = $db->table('lessons')->where('course_id', $id)->get()->getResultArray();
        
        $progressData = [];
        foreach ($lessons as $lesson) {
            $progress = $db->table('lesson_progress')
                ->where('student_id', $studentId)
                ->where('lesson_id', $lesson['id'])
                ->get()
                ->getRowArray();
            
            $progressData[] = [
                'lesson' => $lesson,
                'completed' => $progress ? $progress['completed'] : 0,
                'progress_percent' => $progress ? $progress['progress_percent'] : 0,
                'completed_at' => $progress ? $progress['completed_at'] : null
            ];
        }
        
        return view('student/course-progress', [
            'course' => $course,
            'progressData' => $progressData,
            'overallProgress' => $this->getCourseProgress($studentId, $id),
            'pageTitle' => 'Course Progress'
        ]);
    }
    
    public function certificates()
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Get completed courses
        $completedCourses = $db->table('enrollments')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $studentId)
            ->where('enrollments.status', 'completed')
            ->select('enrollments.*, courses.title, courses.credits')
            ->get()
            ->getResultArray();
        
        return view('student/certificates', [
            'completedCourses' => $completedCourses,
            'pageTitle' => 'My Certificates'
        ]);
    }
    
    public function profile()
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Get student data
        $student = $db->table('users')
            ->where('id', $studentId)
            ->get()
            ->getRowArray();
        
        // Get enrollment history
        $enrollments = $db->table('enrollments')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $studentId)
            ->select('enrollments.*, courses.title')
            ->orderBy('enrollments.created_at', 'DESC')
            ->get()
            ->getResultArray();
        
        return view('student/profile', [
            'student' => $student,
            'enrollments' => $enrollments,
            'pageTitle' => 'My Profile'
        ]);
    }
    
    public function updateProgress()
    {
        if ($this->request->getMethod() === 'POST') {
            $studentId = session()->get('userID');
            $lessonId = $this->request->getPost('lesson_id');
            $progress = $this->request->getPost('progress');
            $completed = $this->request->getPost('completed') ?? 0;
            
            $db = \Config\Database::connect();
            
            // Check if progress record exists
            $existing = $db->table('lesson_progress')
                ->where('student_id', $studentId)
                ->where('lesson_id', $lessonId)
                ->get()
                ->getRowArray();
            
            $data = [
                'progress_percent' => $progress,
                'completed' => $completed,
                'completed_at' => $completed ? date('Y-m-d H:i:s') : null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($existing) {
                $db->table('lesson_progress')
                    ->where('student_id', $studentId)
                    ->where('lesson_id', $lessonId)
                    ->update($data);
            } else {
                $data['student_id'] = $studentId;
                $data['lesson_id'] = $lessonId;
                $data['created_at'] = date('Y-m-d H:i:s');
                $db->table('lesson_progress')->insert($data);
            }
            
            return $this->response->setJSON(['success' => true]);
        }
    }
    
    private function getCourseProgress($studentId, $courseId)
    {
        $db = \Config\Database::connect();
        
        $totalLessons = $db->table('lessons')
            ->where('course_id', $courseId)
            ->countAllResults();
        
        if ($totalLessons == 0) return 0;
        
        $completedLessons = $db->table('lesson_progress')
            ->where('student_id', $studentId)
            ->where('lesson_id IN (SELECT id FROM lessons WHERE course_id = ' . $courseId . ')')
            ->where('completed', 1)
            ->countAllResults();
        
        return round(($completedLessons / $totalLessons) * 100, 2);
    }
    
    public function enroll($courseId)
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Check if student is already enrolled
        $existing = $db->table('enrollments')
            ->where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->get()
            ->getRow();
        
        if ($existing) {
            session()->setFlashdata('error', 'You are already enrolled in this course');
            return redirect()->to('/courses/' . $courseId);
        }
        
        // Check if course exists
        $course = $db->table('courses')
            ->where('id', $courseId)
            ->get()
            ->getRow();
        
        if (!$course) {
            session()->setFlashdata('error', 'Course not found');
            return redirect()->to('/courses/browse');
        }
        
        // Create enrollment
        $data = [
            'user_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('enrollments')->insert($data);
        
        session()->setFlashdata('success', 'Successfully enrolled in course: ' . $course->title);
        return redirect()->to('/student/course/' . $courseId);
    }
    
    public function unenroll($courseId)
    {
        $db = \Config\Database::connect();
        $studentId = session()->get('userID');
        
        // Check if student is enrolled
        $enrollment = $db->table('enrollments')
            ->where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->get()
            ->getRow();
        
        if (!$enrollment) {
            session()->setFlashdata('error', 'You are not enrolled in this course');
            return redirect()->to('/courses/' . $courseId);
        }
        
        // Delete enrollment
        $db->table('enrollments')
            ->where('user_id', $studentId)
            ->where('course_id', $courseId)
            ->delete();
        
        session()->setFlashdata('success', 'Successfully unenrolled from course');
        return redirect()->to('/courses/' . $courseId);
    }
}
