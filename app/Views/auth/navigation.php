<?php
$session = session();
$userRole = $session->get('role');
$username = $session->get('username');
?>

<nav class="navbar navbar-expand-lg navbar-dark 
    <?php if($userRole === 'admin'): ?>navbar-admin<?php 
    elseif($userRole === 'teacher'): ?>navbar-teacher<?php 
    else: ?>navbar-student<?php endif; ?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <?php if($userRole === 'admin'): ?>
                <i class="fas fa-shield-alt"></i> Admin Panel
            <?php elseif($userRole === 'teacher'): ?>
                <i class="fas fa-chalkboard-teacher"></i> Teacher Portal
            <?php else: ?>
                <i class="fas fa-user-graduate"></i> Student Portal
            <?php endif; ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/<?= $userRole ?>/dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                <?php if($userRole === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/courses">
                            <i class="fas fa-book"></i> Manage Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/reports">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                
                <?php elseif($userRole === 'teacher'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/teacher/courses">
                            <i class="fas fa-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/teacher/create-course">
                            <i class="fas fa-plus-circle"></i> Create Course
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/teacher/assignments">
                            <i class="fas fa-tasks"></i> Assignments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/teacher/grades">
                            <i class="fas fa-star"></i> Grades
                        </a>
                    </li>
                
                <?php else: // student ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/student/courses">
                            <i class="fas fa-book"></i> My Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/student/assignments">
                            <i class="fas fa-tasks"></i> Assignments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/student/grades">
                            <i class="fas fa-chart-bar"></i> Grades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/student/schedule">
                            <i class="fas fa-calendar"></i> Schedule
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?= esc($username) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/profile">
                            <i class="fas fa-user-edit"></i> Profile
                        </a></li>
                        <li><a class="dropdown-item" href="/settings">
                            <i class="fas fa-cog"></i> Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar-admin {
    background: linear-gradient(90deg, #dc3545, #c82333);
}
.navbar-teacher {
    background: linear-gradient(90deg, #fd7e14, #e55a00);
}
.navbar-student {
    background: linear-gradient(90deg, #0d6efd, #0b5ed7);
}
</style>