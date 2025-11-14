<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Student extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Authorization check - only student can access
        if ($session->get('role') !== 'student') {
            return redirect()->to('/login')->with('error', 'Access denied. Student privileges required.');
        }

        $notificationModel = new NotificationModel();
        $userId = $session->get('id');

        // Prepare dashboard data
        $data = [
            'title' => 'Student Dashboard',
            'username' => $session->get('username'),
            'role' => $session->get('role'),
            'enrolledCourses' => [
                ['name' => 'Mathematics 101', 'progress' => '75%', 'grade' => 'A-'],
                ['name' => 'Physics Fundamentals', 'progress' => '60%', 'grade' => 'B+'],
                ['name' => 'Chemistry Basics', 'progress' => '45%', 'grade' => 'B']
            ],
            'upcomingDeadlines' => [
                ['assignment' => 'Math Assignment 3', 'due' => '2025-09-25', 'course' => 'Math 101'],
                ['assignment' => 'Physics Lab Report', 'due' => '2025-09-28', 'course' => 'Physics'],
                ['assignment' => 'Chemistry Quiz', 'due' => '2025-10-02', 'course' => 'Chemistry']
            ],
            'recentGrades' => [
                ['assignment' => 'Math Quiz 2', 'grade' => 'A', 'date' => '2025-09-15'],
                ['assignment' => 'Physics Homework', 'grade' => 'B+', 'date' => '2025-09-12'],
                ['assignment' => 'Chemistry Test', 'grade' => 'B', 'date' => '2025-09-10']
            ],
            
            // LAB 8: Add notification data for header
            'unreadCount' => $notificationModel->getUnreadCount($userId),
            'notifications' => $notificationModel->getNotificationsForUser($userId, 5)
        ];

        return view('student/dashboard', $data);
    }
}