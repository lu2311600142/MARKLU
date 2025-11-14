<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Materials extends BaseController
{
    /**
     * Display upload form and handle file upload
     */
    public function upload($courseId)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Check if user is admin or teacher
        $role = $session->get('role');
        if ($role !== 'admin' && $role !== 'teacher') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access.');
        }

        $courseModel = new CourseModel();
        $course = $courseModel->find($courseId);

        if (!$course) {
            return redirect()->to('/dashboard')->with('error', 'Course not found.');
        }

        // Handle POST request (file upload)
        if ($this->request->getMethod() === 'POST') {
            $materialModel = new MaterialModel();

            // Validation rules
            $validationRules = [
                'material_file' => [
                    'label' => 'File',
                    'rules' => 'uploaded[material_file]'
                        . '|max_size[material_file,10240]'
                        . '|ext_in[material_file,pdf,doc,docx,ppt,pptx,xlsx,zip]'
                        . '|mime_in[material_file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip]'
                ]
            ];

            if (!$this->validate($validationRules)) {
                $errors = $this->validator ? $this->validator->getErrors() : ['Invalid file upload.'];
                $msg = !empty($errors) ? implode(' ', array_map('esc', $errors)) : 'Invalid file. Please upload PDF, DOC, DOCX, PPT, PPTX, XLSX, or ZIP files only (max 10MB).';
                return redirect()->back()->withInput()->with('error', $msg);
            }

            $file = $this->request->getFile('material_file');

            if ($file->isValid() && !$file->hasMoved()) {
                // Create uploads directory if it doesn't exist
                $uploadPath = WRITEPATH . 'uploads/materials/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Generate unique filename
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);

                // Save to database
                $data = [
                    'course_id' => $courseId,
                    'file_name' => $file->getClientName(),
                    'file_path' => 'uploads/materials/' . $newName,
                    'uploaded_by' => $session->get('id'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($materialModel->insertMaterial($data)) {
                    return redirect()->back()->with('success', 'Material uploaded successfully!');
                } else {
                    // Delete uploaded file if database insert fails
                    $fullPath = $uploadPath . $newName;
                    if (is_file($fullPath)) {
                        @unlink($fullPath);
                    }
                    return redirect()->back()->with('error', 'Failed to save material information.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to upload file.');
            }
        }

        // Display upload form
        $data = [
            'title' => 'Upload Material',
            'course' => $course
        ];

        return view('materials/upload', $data);
    }

    /**
     * Download material
     */
    public function download($materialId)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $materialModel = new MaterialModel();
        $material = $materialModel->getMaterialWithCourse($materialId);

        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Check if student is enrolled (skip for admin/teacher)
        $role = $session->get('role');
        if ($role === 'student') {
            $enrollmentModel = new EnrollmentModel();
            if (!$enrollmentModel->isAlreadyEnrolled($session->get('id'), $material['course_id'])) {
                return redirect()->back()->with('error', 'You must be enrolled in this course to download materials.');
            }
        }

        // Check if file exists
        $filePath = WRITEPATH . $material['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Force download
        return $this->response->download($filePath, null)->setFileName($material['file_name']);
    }

    /**
     * Delete material
     */
    public function delete($materialId)
    {
        $session = session();

        // Check if user is admin or teacher
        $role = $session->get('role');
        if ($role !== 'admin' && $role !== 'teacher') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access.');
        }

        $materialModel = new MaterialModel();
        $material = $materialModel->find($materialId);

        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Delete file from server (safe)
        $filePath = WRITEPATH . $material['file_path'];
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        // Delete from database
        if ($materialModel->deleteMaterial($materialId)) {
            return redirect()->back()->with('success', 'Material deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete material.');
        }
    }

    /**
     * View materials for a course
     */
    public function view($courseId)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $courseModel = new CourseModel();
        $materialModel = new MaterialModel();

        $course = $courseModel->find($courseId);

        if (!$course) {
            return redirect()->to('/dashboard')->with('error', 'Course not found.');
        }

        // Check if student is enrolled
        if ($session->get('role') === 'student') {
            $enrollmentModel = new EnrollmentModel();
            if (!$enrollmentModel->isAlreadyEnrolled($session->get('id'), $courseId)) {
                return redirect()->to('/dashboard')->with('error', 'You must be enrolled in this course.');
            }
        }

        $materials = $materialModel->getMaterialsByCourse($courseId);

        $data = [
            'title' => 'Course Materials',
            'course' => $course,
            'materials' => $materials,
            'role' => $session->get('role')
        ];

        return view('materials/view', $data);
    }
}