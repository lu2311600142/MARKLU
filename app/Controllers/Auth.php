<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnnouncementModel;
use App\Models\NotificationModel;

class Auth extends BaseController
{
    /**
     * register() - Displays the registration form and processes form submission.
     */
    public function register()
    {
        // Handle POST request (registration submission)
        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();

            $rules = [
                'name' => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Prepare user data
            $userData = [
                'username' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'student' // Default role based on your existing database
            ];

            // Save user data
            try {
                if ($userModel->save($userData)) {
                    return redirect()->to('/login')->with('success', 'Account created successfully! Please login.');
                } else {
                    $errors = $userModel->errors();
                    return redirect()->back()->withInput()->with('errors', $errors ?: ['Registration failed. Please try again.']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Registration error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('errors', ['Registration failed: ' . $e->getMessage()]);
            }
        }

        // Handle GET request (show registration form)
        return view('auth/register');
    }

    /**
     * login() - Displays the login form and processes form submission.
     * Role-based redirection after successful login
     */
    public function login()
    {
        // Handle POST request (login submission)
        if ($this->request->getMethod() === 'POST') {
            $session = session();
            $userModel = new UserModel();

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Validation rules
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Please provide valid email and password.');
            }

            try {
                // Find user by email
                $user = $userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password'])) {
                    // Set session data
                    $sessionData = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                    ];
                    $session->set($sessionData);

                    // Role-based redirection
                    $role = $user['role'];
                    
                    // All role redirections properly configured
                    if ($role === 'student') {
                        return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['username'] . '!');
                    } elseif ($role === 'teacher') {
                        // THIS IS THE FIX - redirects to teacher dashboard
                        return redirect()->to('/teacher/dashboard')->with('success', 'Welcome back, ' . $user['username'] . '!');
                    } elseif ($role === 'admin') {
                        return redirect()->to('/admin/dashboard')->with('success', 'Welcome back, ' . $user['username'] . '!');
                    } else {
                        // Fallback for unknown roles
                        return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['username'] . '!');
                    }
                } else {
                    return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Login error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Login failed. Please try again.');
            }
        }

        // Handle GET request (show login form)
        return view('auth/login');
    }

    /**
     * logout() - Destroys the user's session and redirects them.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    /**
     * dashboard() - A unified dashboard that displays content based on user role.
     * This is kept for backward compatibility, but role-specific dashboards 
     * are now in separate controllers (Teacher.php and Admin.php)
     */
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $userModel = new UserModel();
        $notificationModel = new NotificationModel();
        $userRole = $session->get('role');
        $userId = $session->get('id');

        // Base data for all users
        $data = [
            'title' => 'Dashboard',
            'username' => $session->get('username'),
            'email' => $session->get('email'),
            'role' => $userRole
        ];

        // LAB 8: Add notification data for header
        $data['unreadCount'] = $notificationModel->getUnreadCount($userId);
        $data['notifications'] = $notificationModel->getNotificationsForUser($userId, 5);

        // Initialize all variables to prevent undefined variable errors
        $data['totalUsers'] = 0;
        $data['totalAdmins'] = 0;
        $data['totalTeachers'] = 0;
        $data['totalStudents'] = 0;
        $data['recentUsers'] = [];
        $data['totalCourses'] = 0;
        $data['pendingAssignments'] = 5;
        $data['enrolledCourses'] = [];
        $data['availableCourses'] = [];
        $data['upcomingDeadlines'] = [];
        $data['recentGrades'] = [];
        $data['totalCredits'] = 0;

        // Fetch role-specific data
        switch ($userRole) {
            case 'admin':
                $data['totalUsers'] = $userModel->countAll();
                $data['totalAdmins'] = $userModel->where('role', 'admin')->countAllResults();
                $data['totalTeachers'] = $userModel->where('role', 'teacher')->countAllResults();
                $data['totalStudents'] = $userModel->where('role', 'student')->countAllResults();
                $data['recentUsers'] = $userModel->orderBy('created_at', 'DESC')->limit(5)->find();
                break;

            case 'teacher':
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
                
                $data['totalCourses'] = count($teacherCourses);
                $data['totalStudents'] = $totalStudents;
                $data['pendingAssignments'] = 5;
                $data['teacherCourses'] = $teacherCourses;
                break;

            case 'student':
                // Load models for enrollment
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $courseModel = new \App\Models\CourseModel();
                
                // Get enrolled courses
                $data['enrolledCourses'] = $enrollmentModel->getUserEnrollments($userId);
                
                // Get available courses (not enrolled yet)
                $data['availableCourses'] = $courseModel->getAvailableCourses($userId);
                
                // Calculate total credits
                $totalCredits = 0;
                foreach ($data['enrolledCourses'] as $course) {
                    $totalCredits += 3; // Assuming 3 credits per course
                }
                $data['totalCredits'] = $totalCredits;
                
                // Mock data for deadlines and grades
                $data['upcomingDeadlines'] = [
                    ['assignment' => 'Math Assignment 3', 'due' => '2025-09-25', 'course' => 'Math 101'],
                    ['assignment' => 'Physics Lab Report', 'due' => '2025-09-28', 'course' => 'Physics']
                ];
                $data['recentGrades'] = [
                    ['assignment' => 'Math Quiz 2', 'grade' => 'A', 'date' => '2025-09-15'],
                    ['assignment' => 'Physics Homework', 'grade' => 'B+', 'date' => '2025-09-12']
                ];
                break;
        }

        // Load announcements for all roles
        $announcementModel = new AnnouncementModel();
        $data['announcements'] = $announcementModel->orderBy('created_at', 'DESC')->findAll();

        return view('auth/dashboard', $data);
    }
}