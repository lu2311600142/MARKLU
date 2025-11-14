<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // Better security 

// Home routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication routes (public access)
$routes->match(['get', 'post'], 'register', 'Auth::register');
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Announcements now shown inside the unified dashboard
// Keep legacy URL but point it to the dashboard
$routes->get('announcements', 'Auth::dashboard');

// General dashboard fallback (if needed)
$routes->get('dashboard', 'Auth::dashboard');

// ADMIN ROUTES (Admin role only)
// Protected by both 'auth' and 'roleauth' filters
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
});

// TEACHER ROUTES (Teacher + Admin roles)
// Protected by both 'auth' and 'roleauth' filters
$routes->group('teacher', function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    // Allow teachers to upload materials
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
});

// STUDENT ROUTES (Student role only)
// Protected by 'auth' filter
$routes->group('student', function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});

// COURSE & ENROLLMENT ROUTES
$routes->post('course/enroll', 'Course::enroll');
$routes->get('courses', 'Course::index');

// MATERIAL ROUTES
$routes->get('materials/view/(:num)', 'Materials::view/$1');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');

// Notification routes (LAB 8)
$routes->get('notifications', 'Notifications::get');
$routes->post('notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');
$routes->post('notifications/mark_all_read', 'Notifications::mark_all_read');