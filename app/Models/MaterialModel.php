<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'file_name', 'file_path'];
    protected $useTimestamps = false;
    
    public function insertMaterial($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
    
    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    public function getMaterialById($material_id)
    {
        return $this->find($material_id);
    }
    
    public function deleteMaterial($material_id)
    {
        $material = $this->find($material_id);
        if ($material) {
            // Delete the file from the filesystem
            if (file_exists($material['file_path'])) {
                unlink($material['file_path']);
            }
            // Delete the database record
            return $this->delete($material_id);
        }
        return false;
    }
    
    public function searchMaterials($query, $userId = null, $limit = null)
    {
        $builder = $this->builder()
            ->select('materials.*, courses.title as course_title')
            ->join('courses', 'courses.id = materials.course_id')
            ->like('materials.file_name', $query)
            ->orLike('courses.title', $query)
            ->orderBy('materials.created_at', 'DESC');
            
        // If user is specified, only show materials from courses they're enrolled in
        if ($userId) {
            $db = \Config\Database::connect();
            $enrolledCourses = $db->table('enrollments')
                ->where('user_id', $userId)
                ->get()
                ->getResultArray();
            
            $courseIds = array_column($enrolledCourses, 'course_id');
            if (!empty($courseIds)) {
                $builder->whereIn('materials.course_id', $courseIds);
            }
        }
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}
