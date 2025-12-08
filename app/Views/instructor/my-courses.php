<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $pageTitle ?></h2>
        <a href="<?= base_url('instructor/create-course') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Course
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($courses)): ?>
        <div class="text-center py-5">
            <i class="bi bi-book display-1 text-muted"></i>
            <h3 class="mt-3">No Courses Yet</h3>
            <p class="text-muted">Start by creating your first course.</p>
            <a href="<?= base_url('instructor/create-course') ?>" class="btn btn-primary">
                Create Your First Course
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $course['title'] ?></h5>
                            <p class="card-text text-muted"><?= substr($course['description'], 0, 100) ?>...</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary"><?= $course['credits'] ?> Credits</span>
                                <span class="badge bg-info"><?= $course['studentCount'] ?> Students</span>
                                <span class="badge bg-success"><?= $course['lessonCount'] ?> Lessons</span>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    Status: <span class="badge bg-<?= $course['status'] == 'published' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($course['status']) ?>
                                    </span>
                                </small>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('instructor/course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <div>
                                    <a href="<?= base_url('instructor/edit-course/' . $course['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('instructor/course-analytics/' . $course['id']) ?>" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-graph-up"></i> Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
