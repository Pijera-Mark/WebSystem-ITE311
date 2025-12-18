<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description'];
    protected $useTimestamps = true;
    
    public function searchCourses($query, $limit = null)
    {
        $builder = $this->builder()
            ->like('title', $query)
            ->orLike('description', $query)
            ->orderBy('created_at', 'DESC');
            
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}
