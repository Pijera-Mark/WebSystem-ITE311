<?= $this->extend('working_template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
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
        <div class="col-md-8">
            <h2><?= esc($course['title']) ?></h2>
            <p class="text-muted">
                Created: <?= date('M d, Y', strtotime($course['created_at'])) ?> |
                <?= $enrollmentCount ?> students enrolled
            </p>
            
            <div class="mb-4">
                <h4>Description</h4>
                <p><?= esc($course['description'] ?? 'No description available.') ?></p>
            </div>
            
            <div class="mb-4">
                <h4>Lessons</h4>
                <?php if (empty($lessons)): ?>
                    <p>No lessons available yet.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($lessons as $lesson): ?>
                            <li class="list-group-item">
                                <?= esc($lesson['title']) ?>
                                <?php if ($course['is_enrolled']): ?>
                                    <a href="/student/course-progress/<?= $course['id'] ?>" class="btn btn-sm btn-outline-primary float-end">View</a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Course Actions</h5>
                    
                    <?php if (session()->get('isLoggedIn') && session()->get('userRole') === 'student'): ?>
                        <?php if ($course['is_enrolled']): ?>
                            <div class="d-grid gap-2">
                                <a href="/student/course/<?= $course['id'] ?>" class="btn btn-success">Continue Learning</a>
                                <form action="/unenroll/<?= $course['id'] ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to unenroll from this course?')">Unenroll</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <form action="/enroll/<?= $course['id'] ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary w-100">Enroll in Course</button>
                            </form>
                        <?php endif; ?>
                    <?php elseif (!session()->get('isLoggedIn')): ?>
                        <div class="alert alert-info">
                            <a href="/login">Login</a> to enroll in this course.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Only students can enroll in courses.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
