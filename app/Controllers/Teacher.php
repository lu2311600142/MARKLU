<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\AnnouncementModel;

class Teacher extends BaseController
{
    /**
     * dashboard() - Teacher Dashboard
     * 
     * No need for manual role checks here because:
     * 1. AuthFilter checks if user is logged in
     * 2. RoleAuth filter checks if user has 'teacher' or 'admin' role
     * If they reach this method, they're already authorized!
     */
    public function dashboard()
    {
        $session = session();
        $notificationModel = new NotificationModel();
        $userId = $session->get('id');

        // Load CourseModel to fetch teacher's courses
        $courseModel = new \App\Models\CourseModel();
        
        // Fetch courses where this teacher is the instructor
        $teacherCourses = $courseModel->where('instructor_id', $userId)->findAll();
        
        // Count total students (you can improve this later with proper JOIN)
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $totalStudents = 0;
        foreach ($teacherCourses as $course) {
            $totalStudents += $enrollmentModel->where('course_id', $course['id'])->countAllResults();
        }

        // Prepare dashboard data
        $data = [
            'title' => 'Teacher Dashboard',
            'username' => $session->get('username'),
            'email' => $session->get('email'),
            'role' => $session->get('role'),
            'totalCourses' => count($teacherCourses),
            'totalStudents' => $totalStudents,
            'pendingAssignments' => 5,
            'teacherCourses' => $teacherCourses,
            
            // LAB 8: Add notification data for header
            'unreadCount' => $notificationModel->getUnreadCount($userId),
            'notifications' => $notificationModel->getNotificationsForUser($userId, 5)
        ];

        // Load announcements
        $announcementModel = new AnnouncementModel();
        $data['announcements'] = $announcementModel->orderBy('created_at', 'DESC')->findAll();

        return view('auth/dashboard', $data);  
    }
}