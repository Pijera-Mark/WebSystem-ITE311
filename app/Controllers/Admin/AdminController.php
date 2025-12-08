<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function users()
    {
        $db = \Config\Database::connect();
        
        // Get all users with pagination
        $users = $db->table('users')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
        
        // Get user statistics
        $stats = [
            'totalUsers' => $db->table('users')->countAll(),
            'adminCount' => $db->table('users')->where('role', 'admin')->countAllResults(),
            'instructorCount' => $db->table('users')->where('role', 'instructor')->countAllResults() + $db->table('users')->where('role', 'teacher')->countAllResults(),
            'studentCount' => $db->table('users')->where('role', 'student')->countAllResults()
        ];
        
        return view('admin/users', [
            'users' => $users,
            'stats' => $stats,
            'pageTitle' => 'Manage Users'
        ]);
    }
    
    public function courses()
    {
        $db = \Config\Database::connect();
        
        // Get all courses with instructor info (handle missing instructor_id gracefully)
        try {
            $courses = $db->table('courses')
                ->join('users', 'users.id = courses.instructor_id', 'left')
                ->select('courses.*, users.name as instructor_name')
                ->orderBy('courses.created_at', 'DESC')
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            // Fallback if join fails
            $courses = $db->table('courses')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();
        }
        
        // Get course statistics
        try {
            $stats = [
                'totalCourses' => $db->table('courses')->countAll(),
                'activeCourses' => $db->table('courses')->where('status', 'active')->countAllResults(),
                'totalEnrollments' => $db->table('enrollments')->countAll()
            ];
        } catch (\Exception $e) {
            // Fallback if status column doesn't exist
            $stats = [
                'totalCourses' => $db->table('courses')->countAll(),
                'activeCourses' => $db->table('courses')->countAll(), // Count all as active if no status column
                'totalEnrollments' => $db->table('enrollments')->countAll()
            ];
        }
        
        return view('admin/courses', [
            'courses' => $courses,
            'stats' => $stats,
            'pageTitle' => 'Manage Courses'
        ]);
    }
    
    public function reports()
    {
        $db = \Config\Database::connect();
        
        // System statistics
        $systemStats = [
            'totalUsers' => $db->table('users')->countAll(),
            'totalCourses' => $db->table('courses')->countAll(),
            'totalEnrollments' => $db->table('enrollments')->countAll(),
            'totalLessons' => $db->table('lessons')->countAll(),
            'totalQuizzes' => $db->table('quizzes')->countAll()
        ];
        
        // User distribution by role
        $userDistribution = $db->table('users')
            ->select('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->getResultArray();
        
        // Recent activity
        $recentActivity = [
            'recentUsers' => $db->table('users')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray(),
            'recentCourses' => $db->table('courses')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray(),
            'recentEnrollments' => $db->table('enrollments')
                ->join('users', 'users.id = enrollments.user_id')
                ->join('courses', 'courses.id = enrollments.course_id')
                ->select('enrollments.*, users.name as student_name, courses.title as course_title')
                ->orderBy('enrollments.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray()
        ];
        
        return view('admin/reports', [
            'systemStats' => $systemStats,
            'userDistribution' => $userDistribution,
            'recentActivity' => $recentActivity,
            'pageTitle' => 'System Reports'
        ]);
    }
    
    public function settings()
    {
        // Get system settings (you can expand this with actual settings table)
        $settings = [
            'site_name' => 'ITE311 Learning Management System',
            'site_description' => 'A modern LMS built with CodeIgniter 4',
            'maintenance_mode' => false,
            'allow_registration' => true,
            'email_notifications' => true
        ];
        
        return view('admin/settings', [
            'settings' => $settings,
            'pageTitle' => 'System Settings'
        ]);
    }
    
    public function createUser()
    {
        if ($this->request->getMethod() === 'POST') {
            // Handle user creation
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'role' => 'required|in_list[admin,instructor,teacher,student]',
                'password' => 'required|min_length[6]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'role' => $this->request->getPost('role'),
                    'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $db = \Config\Database::connect();
                $db->table('users')->insert($data);
                
                session()->setFlashdata('success', 'User created successfully');
                return redirect()->to('/admin/users');
            } else {
                return redirect()->back()->with('validation', $this->validator);
            }
        }
        
        return view('admin/create_user');
    }
    
    public function editUser($id)
    {
        $db = \Config\Database::connect();
        
        if ($this->request->getMethod() === 'POST') {
            // Handle user update
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => "required|valid_email|is_unique[users.email,id,$id]",
                'role' => 'required|in_list[admin,instructor,teacher,student]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'role' => $this->request->getPost('role'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Update password if provided
                if ($this->request->getPost('password')) {
                    $data['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }
                
                $db->table('users')->where('id', $id)->update($data);
                
                session()->setFlashdata('success', 'User updated successfully');
                return redirect()->to('/admin/users');
            } else {
                return redirect()->back()->with('validation', $this->validator);
            }
        }
        
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        
        if (!$user) {
            session()->setFlashdata('error', 'User not found');
            return redirect()->to('/admin/users');
        }
        
        return view('admin/edit_user', ['user' => $user]);
    }
    
    public function deleteUser($id)
    {
        $db = \Config\Database::connect();
        
        // Don't allow deletion of self
        if ($id == session()->get('userID')) {
            session()->setFlashdata('error', 'You cannot delete your own account');
            return redirect()->to('/admin/users');
        }
        
        $db->table('users')->where('id', $id)->delete();
        
        session()->setFlashdata('success', 'User deleted successfully');
        return redirect()->to('/admin/users');
    }
}
