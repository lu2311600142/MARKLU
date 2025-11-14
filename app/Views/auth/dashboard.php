<?php
// Safe defaults (avoid undefined var errors)
$role = $role ?? session('role') ?? 'student';
$username = $username ?? session('username') ?? 'User';

// Debug: Check what role we actually have
// Uncomment this line to debug: echo "Current role: " . $role . "<br>";

// If username contains 'instructor' or 'teacher', assume teacher role
if (stripos($username, 'instructor') !== false || stripos($username, 'teacher') !== false) {
    $role = 'teacher';
}

// Alternative: check if role from session needs mapping
if (session('role') === 'instructor') {
    $role = 'teacher';
}

$totalUsers    = $totalUsers    ?? 0;
$totalAdmins   = $totalAdmins   ?? 0;
$totalTeachers = $totalTeachers ?? 0;
$totalStudents = $totalStudents ?? 0;
$recentUsers   = $recentUsers   ?? [];

$totalCourses       = $totalCourses       ?? 0;
$pendingAssignments = $pendingAssignments ?? 0;
$notifications      = $notifications      ?? [];

$enrolledCourses   = $enrolledCourses   ?? [];
$availableCourses  = $availableCourses  ?? [];
$upcomingDeadlines = $upcomingDeadlines ?? [];
$recentGrades      = $recentGrades      ?? [];
$totalCredits      = $totalCredits      ?? 0;

// Set variables that header.php expects
$userRole = $role;
$isLoggedIn = session('isLoggedIn') ?? true;
$title = 'Dashboard';

// Include the header from templates folder
echo view('templates/header', [
    'userRole' => $userRole,
    'username' => $username,
    'isLoggedIn' => $isLoggedIn,
    'title' => $title,
    'role' => $role
]);
?>

<style>
  /* Base */
  body {
    background-color: #eef2f7;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
  }

  /* Header */
  .dashboard-header {
    background: linear-gradient(90deg, #667eea, #764ba2);
    color: white;
    padding: 40px 0;
    text-align: center;
    margin-bottom: 30px;
  }
  .dashboard-header h1 { font-size: 2.2rem; letter-spacing: 1px; }

  /* Cards */
  .card {
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform .2s;
  }
  .card:hover { transform: translateY(-5px); }

  .stat-card small {
    font-size: .8rem;
    text-transform: uppercase;
    font-weight: bold;
    color: #764ba2;
  }
  .stat-card h4 { color: #333; }

  /* Notifications */
  .notification-item {
    border-left: 4px solid #764ba2;
    background: #f9f6ff;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
  }

  /* Footer */
  footer {
    text-align: center;
    padding: 15px 0;
    margin-top: 40px;
    font-size: 0.9rem;
    color: #555;
  }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
  <?php if($role === 'admin'): ?>
    <h1><i class="fas fa-shield-alt"></i> Admin Dashboard</h1>
    <p>Welcome back, <?= esc($username) ?>! Manage your system efficiently.</p>
  <?php elseif($role === 'teacher'): ?>
    <h1><i class="fas fa-chalkboard-teacher"></i> Teacher Dashboard</h1>
    <p>Welcome back, <?= esc($username) ?>! Ready to inspire minds today?</p>
  <?php else: ?>
    <h1><i class="fas fa-user-graduate"></i> Student Dashboard</h1>
    <p>Welcome back, <?= esc($username) ?>! Continue your learning journey.</p>
  <?php endif; ?>
</div>

<!-- Main -->
<div class="container mb-5">
  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <div class="card shadow mb-4">
    <div class="card-header"><strong>Announcements</strong></div>
    <div class="card-body">
      <?php if (!empty($announcements)): ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($announcements as $a): ?>
            <li class="list-group-item">
              <h5 class="mb-1"><?= esc($a['title']) ?></h5>
              <p class="mb-1"><?= esc($a['content']) ?></p>
              <small class="text-muted">Posted on: <?= isset($a['created_at']) ? esc($a['created_at']) : '-' ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No announcements available.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Admin content -->
  <?php if($role === 'admin'): ?>
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Total Users</small><h4><?= esc($totalUsers) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Admins</small><h4><?= esc($totalAdmins) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Teachers</small><h4><?= esc($totalTeachers) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Students</small><h4><?= esc($totalStudents) ?></h4></div>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header"><strong>Recent Users</strong></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
          <tbody>
            <?php if(!empty($recentUsers)): foreach($recentUsers as $u): ?>
              <tr>
                <td><?= esc($u['username']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= esc($u['role']) ?></td>
                <td><?= isset($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : '-' ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="4" class="text-center">No users found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  <!-- Teacher content -->
  <?php elseif($role === 'teacher'): ?>
    
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>My Courses</small><h4><?= esc($totalCourses) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Students</small><h4><?= esc($totalStudents) ?></h4></div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card stat-card p-3"><small>Pending Reviews</small><h4><?= esc($pendingAssignments) ?></h4></div>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header"><strong>My Teaching Courses</strong></div>
      <div class="card-body">
        <?php if(!empty($teacherCourses)): ?>
          <ul class="list-group list-group-flush">
            <?php foreach($teacherCourses as $course): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong><?= esc($course['title']) ?></strong>
                  <br><small class="text-muted"><?= esc($course['description']) ?></small>
                </div>
                <div>
                  <a href="<?= base_url('materials/view/' . $course['id']) ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-folder-open"></i> Manage Materials
                  </a>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-muted">No courses assigned yet.</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header"><strong>Notifications</strong></div>
      <div class="card-body">
        <?php if(!empty($notifications)): ?>
          <?php foreach($notifications as $n): ?>
            <div class="notification-item"><?= esc($n) ?></div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">No notifications at this time.</p>
        <?php endif; ?>
      </div>
    </div>

  <!-- Student content -->
  <?php else: ?>
    
    <div class="row mb-4">
      <div class="col-md-4 mb-3">
        <div class="card stat-card p-3"><small>Enrolled Courses</small><h4><?= count($enrolledCourses) ?></h4></div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card stat-card p-3"><small>Available Courses</small><h4><?= count($availableCourses) ?></h4></div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card stat-card p-3"><small>Total Credits</small><h4><?= $totalCredits ?></h4></div>
      </div>
    </div>

    <!-- Alert container for AJAX messages -->
    <div id="alertContainer"></div>

    <!-- Enrolled Courses -->
    <div class="card shadow mb-4">
      <div class="card-header bg-success text-white"><strong><i class="fas fa-check-circle"></i> Enrolled Courses</strong></div>
      <div class="card-body" id="enrolledCoursesSection">
        <?php if(!empty($enrolledCourses)): ?>
          <div class="row">
            <?php foreach($enrolledCourses as $course): ?>
              <div class="col-md-6 mb-3">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><?= esc($course['title']) ?></h5>
                    <p class="card-text text-muted"><?= esc($course['description']) ?></p>
                    <small class="text-muted">
                      <i class="fas fa-calendar"></i> Enrolled: <?= date('M d, Y', strtotime($course['enrollment_date'])) ?>
                    </small>
                    <div class="mt-3">
                      <a href="<?= base_url('materials/view/' . $course['id']) ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-folder-open"></i> View Materials
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted">You are not enrolled in any courses yet. Check available courses below!</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Available Courses -->
    <div class="card shadow mb-4">
      <div class="card-header bg-primary text-white"><strong><i class="fas fa-book"></i> Available Courses</strong></div>
      <div class="card-body">
        <?php if(!empty($availableCourses)): ?>
          <div class="row" id="availableCoursesSection">
            <?php foreach($availableCourses as $course): ?>
              <div class="col-md-6 mb-3 course-card" data-course-id="<?= $course['id'] ?>">
                <div class="card border-primary">
                  <div class="card-body">
                    <h5 class="card-title"><?= esc($course['title']) ?></h5>
                    <p class="card-text"><?= esc($course['description']) ?></p>
                    <button class="btn btn-primary btn-sm enroll-btn" data-course-id="<?= $course['id'] ?>" data-course-title="<?= esc($course['title']) ?>">
                      <i class="fas fa-user-plus"></i> Enroll
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted">No available courses at the moment. You've enrolled in all courses!</p>
        <?php endif; ?>
      </div>
    </div>

  <?php endif; ?>

</div>

<!-- Footer -->
<footer>
  &copy; 2025 Your ITE311-LU. All rights reserved.
</footer>

<!-- jQuery and AJAX Enrollment Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle Enroll button click
    $('.enroll-btn').on('click', function() {
        const btn = $(this);
        const courseId = btn.data('course-id');
        const courseTitle = btn.data('course-title');
        const courseCard = btn.closest('.course-card');
        
        // Disable button and show loading state
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enrolling...');
        
        // Send AJAX request
        $.ajax({
            url: '<?= base_url('course/enroll') ?>',
            type: 'POST',
            data: {
                course_id: courseId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success alert
                    showAlert('success', response.message);
                    
                    // Remove course card from available section
                    courseCard.fadeOut(400, function() {
                        $(this).remove();
                        
                        // If no more courses, show message
                        if ($('#availableCoursesSection .course-card').length === 0) {
                            $('#availableCoursesSection').html(
                                '<p class="text-muted">No available courses at the moment. You\'ve enrolled in all courses!</p>'
                            );
                        }
                    });
                    
                    // Update stats
                    updateStats();
                    
                    // Add to enrolled section (optional - or reload page)
                    setTimeout(function() {
                        location.reload(); // Reload to show updated enrolled courses
                    }, 1500);
                    
                } else {
                    // Show error alert
                    showAlert('danger', response.message);
                    // Re-enable button
                    btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Enroll');
                }
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showAlert('danger', errorMsg);
                // Re-enable button
                btn.prop('disabled', false).html('<i class="fas fa-user-plus"></i> Enroll');
            }
        });
    });
    
    // Function to show Bootstrap alert
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
    
    // Function to update stats counters
    function updateStats() {
        // Increment enrolled count
        const enrolledCard = $('.stat-card:contains("Enrolled Courses") h4');
        const currentCount = parseInt(enrolledCard.text());
        enrolledCard.text(currentCount + 1);
        
        // Decrement available count
        const availableCard = $('.stat-card:contains("Available Courses") h4');
        const availableCount = parseInt(availableCard.text());
        availableCard.text(availableCount - 1);
        
        // Update credits (assuming 3 credits per course)
        const creditsCard = $('.stat-card:contains("Total Credits") h4');
        const currentCredits = parseInt(creditsCard.text());
        creditsCard.text(currentCredits + 3);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>