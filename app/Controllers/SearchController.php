<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\MaterialModel;

class SearchController extends BaseController
{
    public function index()
    {
        $query = $this->request->getGet('q');
        $type = $this->request->getGet('type', 'all');
        
        if (empty($query)) {
            return view('search/index', [
                'results' => [],
                'query' => '',
                'type' => $type,
                'totalResults' => 0
            ]);
        }
        
        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();
        
        $results = [];
        $totalResults = 0;
        
        // Search courses
        if ($type === 'all' || $type === 'courses') {
            $courses = $courseModel->searchCourses($query);
            foreach ($courses as $course) {
                $results['courses'][] = [
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'type' => 'course',
                    'url' => '/courses/' . $course['id'],
                    'created_at' => $course['created_at']
                ];
            }
            $totalResults += count($courses);
        }
        
        // Search materials (only if user is logged in)
        if (session()->get('isLoggedIn') && ($type === 'all' || $type === 'materials')) {
            $materials = $materialModel->searchMaterials($query, session()->get('userID'));
            foreach ($materials as $material) {
                $results['materials'][] = [
                    'id' => $material['id'],
                    'title' => $material['file_name'],
                    'description' => 'Material for ' . $material['course_title'],
                    'type' => 'material',
                    'url' => '/materials/download/' . $material['id'],
                    'created_at' => $material['created_at']
                ];
            }
            $totalResults += count($materials);
        }
        
        return view('search/index', [
            'results' => $results,
            'query' => $query,
            'type' => $type,
            'totalResults' => $totalResults
        ]);
    }
    
    public function suggestions()
    {
        $query = $this->request->getGet('q');
        
        if (empty($query) || strlen($query) < 2) {
            return $this->response->setJSON([
                'success' => false,
                'suggestions' => []
            ]);
        }
        
        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();
        
        $suggestions = [];
        
        // Get course suggestions
        $courses = $courseModel->searchCourses($query, 5);
        foreach ($courses as $course) {
            $suggestions[] = [
                'title' => $course['title'],
                'type' => 'course',
                'description' => substr($course['description'], 0, 100) . '...',
                'url' => '/courses/' . $course['id']
            ];
        }
        
        // Get material suggestions (only if logged in)
        if (session()->get('isLoggedIn')) {
            $materials = $materialModel->searchMaterials($query, session()->get('userID'), 3);
            foreach ($materials as $material) {
                $suggestions[] = [
                    'title' => $material['file_name'],
                    'type' => 'material',
                    'description' => 'Material for ' . $material['course_title'],
                    'url' => '/materials/download/' . $material['id']
                ];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }
    
    public function quickSearch()
    {
        $query = $this->request->getGet('q');
        
        if (empty($query)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please enter a search term'
            ]);
        }
        
        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();
        
        $results = [];
        
        // Quick course search
        $courses = $courseModel->searchCourses($query, 3);
        foreach ($courses as $course) {
            $results[] = [
                'title' => $course['title'],
                'type' => 'course',
                'description' => substr($course['description'], 0, 80) . '...',
                'url' => '/courses/' . $course['id']
            ];
        }
        
        // Quick material search (only if logged in)
        if (session()->get('isLoggedIn')) {
            $materials = $materialModel->searchMaterials($query, session()->get('userID'), 2);
            foreach ($materials as $material) {
                $results[] = [
                    'title' => $material['file_name'],
                    'type' => 'material',
                    'description' => 'Material for ' . $material['course_title'],
                    'url' => '/materials/download/' . $material['id']
                ];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'results' => $results,
            'count' => count($results)
        ]);
    }
}
