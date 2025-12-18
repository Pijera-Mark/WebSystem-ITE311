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
}
