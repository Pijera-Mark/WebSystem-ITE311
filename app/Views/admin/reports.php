<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2><?= $pageTitle ?></h2>

    <!-- System Statistics -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= $systemStats['totalUsers'] ?></h5>
                    <p class="card-text">Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><?= $systemStats['totalCourses'] ?></h5>
                    <p class="card-text">Courses</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info"><?= $systemStats['totalEnrollments'] ?></h5>
                    <p class="card-text">Enrollments</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning"><?= $systemStats['totalLessons'] ?></h5>
                    <p class="card-text">Lessons</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-danger"><?= $systemStats['totalQuizzes'] ?></h5>
                    <p class="card-text">Quizzes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Users</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($recentActivity['recentUsers'] as $user): ?>
                        <div class="d-flex justify-content-between">
                            <span><?= $user['name'] ?></span>
                            <small class="text-muted"><?= date('M j', strtotime($user['created_at'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Courses</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($recentActivity['recentCourses'] as $course): ?>
                        <div class="d-flex justify-content-between">
                            <span><?= $course['title'] ?></span>
                            <small class="text-muted"><?= date('M j', strtotime($course['created_at'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Enrollments</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($recentActivity['recentEnrollments'] as $enrollment): ?>
                        <div class="mb-2">
                            <small><?= $enrollment['student_name'] ?> → <?= $enrollment['course_title'] ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
