<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Course extends BaseController
{
    /**
     * Display all courses
     */
    public function index()
    {
        $courseModel = new CourseModel();
        $data['courses'] = $courseModel->findAll();
        return view('courses/index', $data);
    }

    /**
     * Handle AJAX enrollment request
     */
    public function enroll()
    {
        $session = session();
        
        // Security Check 1: Authorization - Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ])->setStatusCode(401);
        }

        // Security Check 2: Role Validation - Only students can enroll
        if ($session->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students can enroll in courses.'
            ])->setStatusCode(403);
        }

        // Security Check 3: Input Validation - Validate course_id
        $courseId = $this->request->getPost('course_id');
        
        if (empty($courseId) || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID provided.'
            ])->setStatusCode(400);
        }

        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        
        // Security Check 4: Course Existence - Verify course exists
        $course = $courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Security Check 5: Data Tampering Prevention - Use session user ID, not client-supplied
        $userId = $session->get('id');

        // Check if already enrolled
        if ($enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ])->setStatusCode(409);
        }

        // Enroll the user
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        try {
            if ($enrollmentModel->enrollUser($enrollmentData)) {
                // Create a notification for the student (Lab 8 - Step 7)
                try {
                    $notifModel = new \App\Models\NotificationModel();
                    $notifModel->insert([
                        'user_id'   => (int)$userId,
                        'message'   => 'You have been enrolled in ' . $course['title'],
                        'is_read'   => 0,
                        'created_at'=> date('Y-m-d H:i:s'),
                    ]);
                } catch (\Throwable $t) {
                    log_message('error', 'Notification create failed: ' . $t->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Successfully enrolled in ' . esc($course['title']) . '!',  
                    'course_title' => $course['title']  
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to enroll. Please try again.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during enrollment.'
            ])->setStatusCode(500);
        }
    }
}