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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= $stats['totalCourses'] ?></h5>
                    <p class="card-text">Total Courses</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><?= $stats['activeCourses'] ?></h5>
                    <p class="card-text">Active Courses</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info"><?= $stats['totalEnrollments'] ?></h5>
                    <p class="card-text">Total Enrollments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Instructor</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No courses found. Create your first course to get started.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?= $course['id'] ?></td>
                                    <td><?= $course['title'] ?></td>
                                    <td><?= substr($course['description'], 0, 50) ?>...</td>
                                    <td><?= $course['instructor_name'] ?? 'Not Assigned' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $course['status'] == 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($course['status'] ?? 'draft') ?>
                                        </span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($course['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('instructor/edit-course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="<?= base_url('instructor/course-analytics/' . $course['id']) ?>" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-graph-up"></i> Analytics
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
