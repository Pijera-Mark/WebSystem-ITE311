<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date'];
    protected $useTimestamps = false;
    
    public function enrollUser($data)
    {
        $data['enrollment_date'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
    
    public function getUserEnrollments($user_id)
    {
        $result = $this->select('enrollments.*, courses.title, courses.description')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $user_id)
            ->findAll();
        
        return $result;
    }
    
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        $result = $this->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->first();
        
        return $result ? true : false;
    }
}
