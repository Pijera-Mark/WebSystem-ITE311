<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $pageTitle ?></h2>
        <a href="<?= base_url('admin/create-user') ?>" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Add User
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= $stats['totalUsers'] ?></h5>
                    <p class="card-text">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><?= $stats['adminCount'] ?></h5>
                    <p class="card-text">Admins</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info"><?= $stats['instructorCount'] ?></h5>
                    <p class="card-text">Instructors</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning"><?= $stats['studentCount'] ?></h5>
                    <p class="card-text">Students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'instructor' ? 'primary' : 'success') ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit-user/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <?php if ($user['id'] != session()->get('userID')): ?>
                                        <a href="<?= base_url('admin/delete-user/' . $user['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
