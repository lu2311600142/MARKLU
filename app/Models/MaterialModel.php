<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'uploaded_by', 'created_at'];
    protected $useTimestamps = false;

    /**
     * Insert a new material record
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     */
    public function getMaterialsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get a single material by ID
     */
    public function getMaterialById($materialId)
    {
        return $this->find($materialId);
    }

    /**
     * Delete a material record
     */
    public function deleteMaterial($materialId)
    {
        return $this->delete($materialId);
    }

    /**
     * Get material with course information
     */
    public function getMaterialWithCourse($materialId)
    {
        return $this->select('materials.*, courses.title as course_title')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->where('materials.id', $materialId)
                    ->first();
    }
}