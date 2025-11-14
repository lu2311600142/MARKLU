<?php
// Set variables for header
$title = 'Course Materials';
$userRole = session('role') ?? 'student';
$username = session('username') ?? 'User';
$isLoggedIn = session('isLoggedIn') ?? true;

echo view('templates/header', [
    'title' => $title,
    'userRole' => $userRole,
    'username' => $username,
    'isLoggedIn' => $isLoggedIn,
    'role' => $userRole
]);
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-folder-open"></i> <?= esc($course['title']) ?> - Course Materials</h4>
            <?php if($role === 'admin' || $role === 'teacher'): ?>
                <?php $uploadBase = $role === 'admin' ? 'admin' : 'teacher'; ?>
                <a href="<?= base_url($uploadBase . '/course/' . $course['id'] . '/upload') ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-upload"></i> Upload New Material
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(!empty($materials)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-file"></i> File Name</th>
                                <th><i class="fas fa-calendar"></i> Uploaded Date</th>
                                <th class="text-center"><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($materials as $material): ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        <?= esc($material['file_name']) ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($material['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <?php if($role === 'admin' || $role === 'teacher'): ?>
                                            <a href="<?= base_url('materials/delete/' . $material['id']) ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this material?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No materials available for this course yet.
                </div>
            <?php endif; ?>

            <div class="mt-3">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>