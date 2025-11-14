<!DOCTYPE html>
<html>
<head>
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Announcements</h2>
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Announcements List -->
        <?php if (empty($announcements)): ?>
            <p>No announcements available.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($announcements as $announcement): ?>
                    <li class="list-group-item">
                        <h5><?= esc($announcement['title']) ?></h5>
                        <p><?= esc($announcement['content']) ?></p>
                        <small class="text-muted">Posted on: <?= $announcement['created_at'] ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>