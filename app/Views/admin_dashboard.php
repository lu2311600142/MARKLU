<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                <h3><i class="fas fa-shield-alt"></i> Admin Portal</h3>
            </div>
            <div class="card-body">
                <h2>Welcome, Admin!</h2>
                <p class="lead">You have successfully logged in as an administrator.</p>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="mt-4">
                    <h5>Quick Actions:</h5>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('announcements') ?>" class="btn btn-primary">
                            <i class="fas fa-bullhorn"></i> View Announcements
                        </a>
                        <a href="<?= base_url('logout') ?>" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>