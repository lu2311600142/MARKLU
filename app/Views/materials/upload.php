<?php
// Set variables for header
$title = 'Upload Material';
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-upload"></i> Upload Material for <?= esc($course['title']) ?></h4>
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

                    <form action="<?= base_url('admin/course/' . $course['id'] . '/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="material_file" class="form-label">Select File</label>
                            <input type="file" class="form-control" id="material_file" name="material_file" required>
                            <small class="text-muted">Allowed: PDF, DOC, DOCX, PPT, PPTX, XLSX, ZIP (Max 10MB)</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Material
                            </button>
                            <a href="<?= base_url('materials/view/' . $course['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Materials
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>