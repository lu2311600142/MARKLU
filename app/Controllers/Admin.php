<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Models\AnnouncementModel;

class Admin extends BaseController
{
    /**
     * dashboard() - Admin Dashboard
     * 
     * No need for manual role checks here because:
     * 1. AuthFilter checks if user is logged in
     * 2. RoleAuth filter checks if user has 'admin' role
     * If they reach this method, they're already authorized!
     */
    public function dashboard()
    {
        $session = session();
        $userModel = new UserModel();
        $notificationModel = new NotificationModel();
        $userId = $session->get('id');
        
        // Prepare dashboard data
        $data = [
            'title' => 'Admin Dashboard',
            'username' => $session->get('username'),
            'email' => $session->get('email'),
            'role' => $session->get('role'),
            'totalUsers' => $userModel->countAll(),
            'totalAdmins' => $userModel->where('role', 'admin')->countAllResults(),
            'totalTeachers' => $userModel->where('role', 'teacher')->countAllResults(),
            'totalStudents' => $userModel->where('role', 'student')->countAllResults(),
            'recentUsers' => $userModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            
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