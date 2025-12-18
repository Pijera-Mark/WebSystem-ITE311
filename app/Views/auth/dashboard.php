<?= $this->extend('template') ?>

<?php $title = 'Dashboard - ' . ucfirst(session()->get('role')); ?>

<?= $this->section('content') ?>


<style>
    body {
        background: #f8f9fa;
    }
    .dashboard-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-top: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-top: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .info-card:hover {
        transform: translateY(-5px);
    }
    .info-card .icon {
        font-size: 3rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .user-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
    }
</style>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="user-avatar">
                        <?= strtoupper(substr(session()->get('name'), 0, 1)) ?>
                    </div>
                </div>
                <div class="col">
                    <h2 class="mb-1">Welcome, <?= session()->get('name') ?>!</h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-envelope"></i> <?= session()->get('email') ?> | 
                        <i class="bi bi-shield-check"></i> Role: <span class="badge bg-primary"><?= ucfirst(session()->get('role')) ?></span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Role-specific Info Cards -->
        <div class="row mt-4">
            <?php if ($userRole === 'admin'): ?>
                <!-- Admin Dashboard Cards -->
                <div class="col-md-3">
                    <div class="info-card text-center">
                        <i class="bi bi-people icon"></i>
                        <h4 class="mt-3">Total Users</h4>
                        <p class="text-muted">Registered users in system</p>
                        <h3 class="text-primary"><?= $roleData['totalUsers'] ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center">
                        <i class="bi bi-book icon"></i>
                        <h4 class="mt-3">Total Courses</h4>
                        <p class="text-muted">Available courses</p>
                        <h3 class="text-success"><?= $roleData['totalCourses'] ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center">
                        <i class="bi bi-person-check icon"></i>
                        <h4 class="mt-3">Enrollments</h4>
                        <p class="text-muted">Total enrollments</p>
                        <h3 class="text-info"><?= $roleData['totalEnrollments'] ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card text-center">
                        <i class="bi bi-graph-up icon"></i>
                        <h4 class="mt-3">System Status</h4>
                        <p class="text-muted">All systems operational</p>
                        <span class="badge bg-success">Online</span>
                    </div>
                </div>
            <?php elseif ($userRole === 'instructor' || $userRole === 'teacher'): ?>
                <!-- Instructor Dashboard Cards -->
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <i class="bi bi-book icon"></i>
                        <h4 class="mt-3">My Courses</h4>
                        <p class="text-muted">Courses you're teaching</p>
                        <h3 class="text-primary"><?= $roleData['totalCourses'] ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <i class="bi bi-people icon"></i>
                        <h4 class="mt-3">Total Students</h4>
                        <p class="text-muted">Students across all courses</p>
                        <h3 class="text-success"><?= $roleData['totalStudents'] ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <i class="bi bi-calendar-check icon"></i>
                        <h4 class="mt-3">Schedule</h4>
                        <p class="text-muted">Today's classes</p>
                        <span class="badge bg-info">2 Classes</span>
                    </div>
                </div>
            <?php else: ?>
                <!-- Student Dashboard Cards -->
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <i class="bi bi-book icon"></i>
                        <h4 class="mt-3">Enrolled Courses</h4>
                        <p class="text-muted">Courses you're taking</p>
                        <h3 class="text-primary"><?= $roleData['totalCourses'] ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <i class="bi bi-trophy icon"></i>
                        <h4 class="mt-3">Achievements</h4>
                        <p class="text-muted">Your accomplishments</p>
                        <span class="badge bg-warning">3 Badges</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <i class="bi bi-clock-history icon"></i>
                        <h4 class="mt-3">Progress</h4>
                        <p class="text-muted">Overall learning progress</p>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" style="width: 65%">65%</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Role-specific Content -->
        <div class="row mt-4 mb-5">
            <?php if ($userRole === 'admin'): ?>
                <!-- Admin Content -->
                <div class="col-md-6">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-people"></i> Recent Users</h4>
                        <?php if (!empty($roleData['recentUsers'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($roleData['recentUsers'] as $user): ?>
                                            <tr>
                                                <td><?= $user['name'] ?></td>
                                                <td><?= $user['email'] ?></td>
                                                <td><span class="badge bg-primary"><?= ucfirst($user['role']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No recent users found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-gear"></i> Admin Actions</h4>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add User</a>
                            <a href="#" class="btn btn-success"><i class="bi bi-plus-circle"></i> Create Course</a>
                            <a href="#" class="btn btn-info"><i class="bi bi-graph-up"></i> View Reports</a>
                            <a href="#" class="btn btn-warning"><i class="bi bi-gear"></i> System Settings</a>
                        </div>
                    </div>
                </div>
            <?php elseif ($userRole === 'instructor' || $userRole === 'teacher'): ?>
                <!-- Instructor Content -->
                <div class="col-md-8">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-book"></i> My Courses</h4>
                        <?php if (!empty($roleData['courses'])): ?>
                            <div class="row">
                                <?php foreach ($roleData['courses'] as $course): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $course['title'] ?></h5>
                                                <p class="card-text text-muted"><?= $course['description'] ?></p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-info"><?= $course['credits'] ?> Credits</span>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">Manage</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">You haven't created any courses yet.</p>
                            <a href="#" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Create Course</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-calendar3"></i> Quick Actions</h4>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary"><i class="bi bi-plus-circle"></i> New Course</a>
                            <a href="#" class="btn btn-success"><i class="bi bi-clipboard-check"></i> Grade Submissions</a>
                            <a href="#" class="btn btn-info"><i class="bi bi-chat-dots"></i> Messages</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Student Content -->
                <div class="col-md-6">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-book"></i> Enrolled Courses</h4>
                        <div id="enrolled-courses">
                            <?php if (!empty($roleData['enrolledCourses'])): ?>
                                <div class="list-group">
                                    <?php foreach ($roleData['enrolledCourses'] as $enrollment): ?>
                                        <div class="list-group-item">
                                            <h6 class="mb-1"><?= $enrollment['title'] ?></h6>
                                            <p class="mb-1 text-muted"><?= substr($enrollment['description'], 0, 100) ?>...</p>
                                            <small class="text-success">Enrolled on <?= date('M d, Y', strtotime($enrollment['enrollment_date'])) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">You haven't enrolled in any courses yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-plus-circle"></i> Available Courses</h4>
                        <div id="available-courses">
                            <?php if (!empty($roleData['availableCourses'])): ?>
                                <div class="list-group">
                                    <?php foreach ($roleData['availableCourses'] as $course): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?= $course['title'] ?></h6>
                                                <p class="mb-1 text-muted"><?= substr($course['description'], 0, 80) ?>...</p>
                                            </div>
                                            <button class="btn btn-primary btn-sm enroll-btn" data-course-id="<?= $course['id'] ?>">
                                                Enroll
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No available courses at the moment.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Materials Section for Students -->
        <?php if ($userRole === 'student'): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-card">
                        <h4 class="mb-4"><i class="bi bi-folder"></i> Course Materials</h4>
                        <?php if (!empty($roleData['materials'])): ?>
                            <div class="list-group">
                                <?php foreach ($roleData['materials'] as $material): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="bi bi-file-earmark"></i> 
                                                <?= esc($material['file_name']) ?>
                                            </h6>
                                            <small class="text-muted">
                                                Course: <?= esc($material['course_title']) ?> | 
                                                Uploaded: <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                            </small>
                                        </div>
                                        <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="bi bi-folder2-open" style="font-size: 2rem;"></i>
                                <p class="mt-2">No materials available for your enrolled courses.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- User Information Card -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="info-card">
                    <h4 class="mb-4"><i class="bi bi-info-circle"></i> Your Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">User ID:</label>
                                <p class="text-muted">#<?= session()->get('userID') ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name:</label>
                                <p class="text-muted"><?= session()->get('name') ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address:</label>
                                <p class="text-muted"><?= session()->get('email') ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Role:</label>
                                <p class="text-muted"><?= ucfirst(session()->get('role')) ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a href="<?= base_url('logout') ?>" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and AJAX Enrollment Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Handle enrollment button clicks
        $('.enroll-btn').on('click', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var courseId = $btn.data('course-id');
            var $courseItem = $btn.closest('.list-group-item');
            
            // Disable button to prevent multiple clicks
            $btn.prop('disabled', true).text('Enrolling...');
            
            // Send AJAX request to enroll
            $.post('/course/enroll', {
                course_id: courseId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            }, function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('success', response.message);
                    
                    // Move course to enrolled courses section
                    var $enrolledCourses = $('#enrolled-courses .list-group');
                    if ($enrolledCourses.length === 0) {
                        $('#enrolled-courses').html('<div class="list-group"></div>');
                        $enrolledCourses = $('#enrolled-courses .list-group');
                    }
                    
                    var courseTitle = $courseItem.find('h6').text();
                    var courseDesc = $courseItem.find('p').text();
                    var currentDate = new Date().toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                    
                    $enrolledCourses.append(
                        '<div class="list-group-item">' +
                        '<h6 class="mb-1">' + courseTitle + '</h6>' +
                        '<p class="mb-1 text-muted">' + courseDesc + '</p>' +
                        '<small class="text-success">Enrolled on ' + currentDate + '</small>' +
                        '</div>'
                    );
                    
                    // Remove from available courses
                    $courseItem.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if no more available courses
                        if ($('#available-courses .list-group-item').length === 0) {
                            $('#available-courses').html('<p class="text-muted">No available courses at the moment.</p>');
                        }
                    });
                } else {
                    // Show error message
                    showAlert('danger', response.message);
                    // Re-enable button
                    $btn.prop('disabled', false).text('Enroll');
                }
            }).fail(function(xhr) {
                // Handle AJAX errors
                var errorMessage = 'An error occurred while enrolling.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('danger', errorMessage);
                // Re-enable button
                $btn.prop('disabled', false).text('Enroll');
            });
        });
        
        // Function to show Bootstrap alerts
        function showAlert(type, message) {
            var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show mt-3" role="alert">' +
                           '<i class="bi bi-' + (type === 'success' ? 'check-circle' : 'exclamation-triangle') + '-fill me-2"></i>' +
                           message +
                           '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                           '</div>';
            
            // Insert alert after the welcome message
            $('.dashboard-header').after(alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }
    });
    </script>

<?= $this->endSection() ?>
