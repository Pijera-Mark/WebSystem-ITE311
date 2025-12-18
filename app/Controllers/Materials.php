<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\CourseModel;

class Materials extends BaseController
{
    public function upload($course_id)
    {
        // Check if user is logged in and has appropriate role
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page');
        }
        
        $userRole = session()->get('role');
        if (!in_array($userRole, ['admin', 'instructor', 'teacher'])) {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $materialModel = new MaterialModel();
        $courseModel = new CourseModel();
        
        // Check if course exists
        $course = $courseModel->find($course_id);
        if (!$course) {
            return redirect()->to('/dashboard')->with('error', 'Course not found');
        }
        
        // Handle file upload
        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('material_file');
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Create upload directory if it doesn't exist
                $uploadPath = WRITEPATH . 'uploads/materials/' . $course_id;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Generate unique filename
                $newName = $file->getRandomName();
                
                // Move the file
                if ($file->move($uploadPath, $newName)) {
                    // Save to database
                    $materialData = [
                        'course_id' => $course_id,
                        'file_name' => $file->getClientName(),
                        'file_path' => $uploadPath . '/' . $newName
                    ];
                    
                    if ($materialModel->insertMaterial($materialData)) {
                        return redirect()->to('/admin/courses')->with('success', 'Material uploaded successfully');
                    } else {
                        return redirect()->to('/admin/course/' . $course_id . '/upload')->with('error', 'Failed to save material information');
                    }
                } else {
                    return redirect()->to('/admin/course/' . $course_id . '/upload')->with('error', 'Failed to upload file');
                }
            } else {
                return redirect()->to('/admin/course/' . $course_id . '/upload')->with('error', 'Please select a valid file');
            }
        }
        
        // Display upload form
        return view('materials/upload', [
            'course' => $course,
            'course_id' => $course_id
        ]);
    }
    
    public function delete($material_id)
    {
        // Check if user is logged in and has appropriate role
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page');
        }
        
        $userRole = session()->get('role');
        if (!in_array($userRole, ['admin', 'instructor', 'teacher'])) {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $materialModel = new MaterialModel();
        
        if ($materialModel->deleteMaterial($material_id)) {
            return redirect()->back()->with('success', 'Material deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete material');
        }
    }
    
    public function download($material_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page');
        }
        
        $materialModel = new MaterialModel();
        $material = $materialModel->getMaterialById($material_id);
        
        if (!$material) {
            return redirect()->to('/dashboard')->with('error', 'Material not found');
        }
        
        // Check if user is enrolled in the course or is admin/instructor
        $userRole = session()->get('role');
        $userId = session()->get('userID');
        
        if (in_array($userRole, ['admin', 'instructor', 'teacher'])) {
            // Admin and instructors can download any material
            $canDownload = true;
        } else {
            // Students must be enrolled in the course
            $db = \Config\Database::connect();
            $enrollment = $db->table('enrollments')
                ->where('user_id', $userId)
                ->where('course_id', $material['course_id'])
                ->get()
                ->getRow();
            $canDownload = $enrollment ? true : false;
        }
        
        if (!$canDownload) {
            return redirect()->to('/dashboard')->with('error', 'You are not authorized to download this material');
        }
        
        // Check if file exists
        if (!file_exists($material['file_path'])) {
            return redirect()->to('/dashboard')->with('error', 'File not found');
        }
        
        // Force download
        return $this->response->download($material['file_path'], null, true)
            ->setFileName($material['file_name']);
    }
}
