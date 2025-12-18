<?= $this->extend('working_template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Browse Courses</h2>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <?php if (empty($courses)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No courses available at the moment.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($course['title']) ?></h5>
                            <p class="card-text"><?= esc(substr($course['description'] ?? '', 0, 100)) ?>...</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Created: <?= date('M d, Y', strtotime($course['created_at'])) ?>
                                </small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if (session()->get('isLoggedIn') && session()->get('userRole') === 'student'): ?>
                                    <?php if ($course['is_enrolled']): ?>
                                        <a href="/student/course/<?= $course['id'] ?>" class="btn btn-success btn-sm">View Course</a>
                                    <?php else: ?>
                                        <form action="/enroll/<?= $course['id'] ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-primary btn-sm">Enroll</button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="/courses/<?= $course['id'] ?>" class="btn btn-info btn-sm">View Details</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
