<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2><?= $pageTitle ?></h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($courses)): ?>
        <div class="text-center py-5">
            <i class="bi bi-book display-1 text-muted"></i>
            <h3 class="mt-3">No Courses Yet</h3>
            <p class="text-muted">Browse and enroll in courses to start learning.</p>
            <a href="<?= base_url('courses/browse') ?>" class="btn btn-primary">
                Browse Courses
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
                                <small class="text-muted">Instructor: <?= $course['instructor_name'] ?></small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Progress</small>
                                    <span class="badge bg-primary"><?= $course['progress'] ?>%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?= $course['progress'] ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <span class="badge bg-info"><?= $course['credits'] ?> Credits</span>
                                <span class="badge bg-success"><?= $course['lessons_count'] ?> Lessons</span>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('student/course/' . $course['course_id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-play-circle"></i> Continue
                                </a>
                                <a href="<?= base_url('student/course-progress/' . $course['course_id']) ?>" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-graph-up"></i> Progress
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
