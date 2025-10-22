<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Authentication System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= session()->get('name') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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

        <!-- Info Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="info-card text-center">
                    <i class="bi bi-person-check icon"></i>
                    <h4 class="mt-3">Account Status</h4>
                    <p class="text-muted">Your account is active and verified</p>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center">
                    <i class="bi bi-shield-lock icon"></i>
                    <h4 class="mt-3">Security</h4>
                    <p class="text-muted">Your account is secured with encryption</p>
                    <span class="badge bg-info">Protected</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center">
                    <i class="bi bi-clock-history icon"></i>
                    <h4 class="mt-3">Last Login</h4>
                    <p class="text-muted">You logged in just now</p>
                    <span class="badge bg-secondary">Today</span>
                </div>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="row mt-4 mb-5">
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
