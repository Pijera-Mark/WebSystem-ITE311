<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Course extends BaseController
{
    public function enroll()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }
        
        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        if (!$course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID is required'
            ]);
        }
        
        // Validate course_id is numeric
        if (!is_numeric($course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID'
            ]);
        }
        
        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();
        
        // Get logged-in user ID from session
        $user_id = session()->get('userID');
        
        // Check if course exists
        $course = $courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found'
            ]);
        }
        
        // Check if user is already enrolled
        if ($enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course'
            ]);
        }
        
        // Insert new enrollment record
        $enrollment_data = [
            'user_id' => $user_id,
            'course_id' => $course_id
        ];
        
        if ($enrollmentModel->enrollUser($enrollment_data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully enrolled in course'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll in course'
            ]);
        }
    }
}
