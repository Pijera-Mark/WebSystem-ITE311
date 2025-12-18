<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ITE311 - Web System' ?></title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        footer {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="bi bi-code-slash"></i> ITE311
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (session() && session()->get('isLoggedIn')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('dashboard') ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- Search Bar -->
                        <li class="nav-item">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" 
                                       class="form-control" 
                                       id="navSearchInput"
                                       placeholder="Quick search..."
                                       autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" id="navSearchBtn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            
                            <!-- Quick Search Results Dropdown -->
                            <div class="dropdown-menu dropdown-menu-end" id="navSearchResults" style="display: none; min-width: 350px;">
                                <div class="dropdown-header">Quick Search Results</div>
                                <div id="navSearchList">
                                    <!-- Results will be loaded here -->
                                </div>
                            </div>
                        </li>
                        
                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i> 
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 350px;" id="notificationDropdown">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span>Notifications</span>
                                    <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn">Mark all as read</button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li id="notificationList">
                                    <div class="text-center p-3">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center" href="<?= base_url('notifications') ?>">
                                        View all notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <!-- Admin Navigation -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i> Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('admin/users') ?>"><i class="bi bi-people"></i> Manage Users</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/courses') ?>"><i class="bi bi-book"></i> Manage Courses</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/reports') ?>"><i class="bi bi-graph-up"></i> Reports</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear"></i> Settings</a></li>
                                </ul>
                            </li>
                        <?php elseif (session()->get('role') === 'instructor' || session()->get('role') === 'teacher'): ?>
                            <!-- Instructor Navigation -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="instructorDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-mortarboard"></i> Teaching
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('instructor/create-course') ?>"><i class="bi bi-plus-circle"></i> Create Course</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('instructor/grade-submissions') ?>"><i class="bi bi-clipboard-check"></i> Grade Submissions</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('instructor/reports') ?>"><i class="bi bi-graph-up"></i> Reports</a></li>
                                </ul>
                            </li>
                        <?php elseif (session()->get('role') === 'student'): ?>
                            <!-- Student Navigation -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="studentDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-book"></i> Learning
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('courses/browse') ?>"><i class="bi bi-search"></i> Browse Courses</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('student/my-courses') ?>"><i class="bi bi-journal-text"></i> My Courses</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('student/certificates') ?>"><i class="bi bi-award"></i> Certificates</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('logout') ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>ITE311 - Web System Development</h5>
                    <p class="mb-0">Built with CodeIgniter 4 and Bootstrap 5</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?= date('Y') ?> All rights reserved.</p>
                    <small>Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery for Notifications -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?php if (session() && session()->get('isLoggedIn')): ?>
    <script>
    $(document).ready(function() {
        let notificationInterval;
        
        // Load notifications immediately
        loadNotifications();
        
        // Set up interval to check for new notifications every 30 seconds
        notificationInterval = setInterval(function() {
            checkUnreadCount();
        }, 30000);
        
        // Load notifications when dropdown is opened
        $('#notificationDropdown').on('show.bs.dropdown', function() {
            loadNotifications();
        });
        
        // Mark all as read
        $('#markAllReadBtn').on('click', function(e) {
            e.stopPropagation();
            markAllAsRead();
        });
        
        function loadNotifications() {
            $.ajax({
                url: '/notifications/get',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateNotificationBadge(response.unread_count);
                        displayNotifications(response.notifications);
                    }
                },
                error: function() {
                    console.log('Error loading notifications');
                }
            });
        }
        
        function checkUnreadCount() {
            $.ajax({
                url: '/notifications/unread-count',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateNotificationBadge(response.unread_count);
                    }
                },
                error: function() {
                    console.log('Error checking unread count');
                }
            });
        }
        
        function updateNotificationBadge(count) {
            const badge = $('#notificationBadge');
            if (count > 0) {
                badge.text(count);
                badge.show();
            } else {
                badge.hide();
            }
        }
        
        function displayNotifications(notifications) {
            const notificationList = $('#notificationList');
            notificationList.empty();
            
            if (notifications.length === 0) {
                notificationList.html(`
                    <div class="text-center p-3 text-muted">
                        <i class="bi bi-bell-slash" style="font-size: 1.5rem;"></i>
                        <p class="mt-2 mb-0">No notifications</p>
                    </div>
                `);
            } else {
                notifications.forEach(function(notification) {
                    const notificationItem = `
                        <li class="dropdown-item notification-item ${!notification.is_read ? 'unread' : ''}" data-id="${notification.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 ${!notification.is_read ? 'fw-bold' : ''}">
                                        ${getNotificationIcon(notification.type)}
                                        ${notification.title}
                                    </h6>
                                    <p class="mb-1 small">${notification.message}</p>
                                    <small class="text-muted">${notification.time_ago}</small>
                                </div>
                                ${!notification.is_read ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                    `;
                    notificationList.append(notificationItem);
                });
                
                // Add click handler to mark as read
                $('.notification-item').on('click', function() {
                    const notificationId = $(this).data('id');
                    markAsRead(notificationId);
                });
            }
        }
        
        function getNotificationIcon(type) {
            const icons = {
                'enrollment': '<i class="bi bi-person-plus text-primary"></i>',
                'material': '<i class="bi bi-file-earmark text-success"></i>',
                'system': '<i class="bi bi-gear text-warning"></i>',
                'info': '<i class="bi bi-info-circle text-info"></i>'
            };
            return icons[type] || icons['info'];
        }
        
        function markAsRead(notificationId) {
            $.ajax({
                url: '/notifications/mark-read',
                method: 'POST',
                data: {
                    notification_id: notificationId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadNotifications();
                    }
                },
                error: function() {
                    console.log('Error marking notification as read');
                }
            });
        }
        
        function markAllAsRead() {
            $.ajax({
                url: '/notifications/mark-all-read',
                method: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadNotifications();
                    }
                },
                error: function() {
                    console.log('Error marking all notifications as read');
                }
            });
        }
        
        // Clean up interval when page is unloaded
        $(window).on('beforeunload', function() {
            if (notificationInterval) {
                clearInterval(notificationInterval);
            }
        });
        
        // Navigation search functionality
        let navSearchTimeout;
        const navSearchInput = $('#navSearchInput');
        const navSearchResults = $('#navSearchResults');
        const navSearchBtn = $('#navSearchBtn');
        const navSearchList = $('#navSearchList');
        
        // Navigation search with debouncing
        navSearchInput.on('input', function() {
            const query = $(this).val().trim();
            
            clearTimeout(navSearchTimeout);
            
            if (query.length < 2) {
                navSearchResults.hide();
                return;
            }
            
            navSearchTimeout = setTimeout(function() {
                fetchNavSearchResults(query);
            }, 300);
        });
        
        // Search button click
        navSearchBtn.on('click', function() {
            const query = navSearchInput.val().trim();
            if (query.length >= 2) {
                window.location.href = '/search?q=' + encodeURIComponent(query);
            }
        });
        
        // Enter key in search input
        navSearchInput.on('keypress', function(e) {
            if (e.which === 13) {
                const query = $(this).val().trim();
                if (query.length >= 2) {
                    window.location.href = '/search?q=' + encodeURIComponent(query);
                }
            }
        });
        
        // Hide search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.input-group').length && !$(e.target).closest('#navSearchResults').length) {
                navSearchResults.hide();
            }
        });
        
        function fetchNavSearchResults(query) {
            $.ajax({
                url: '/search/quick',
                method: 'GET',
                data: { q: query },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        displayNavSearchResults(response.results);
                    } else {
                        navSearchResults.hide();
                    }
                },
                error: function() {
                    navSearchResults.hide();
                }
            });
        }
        
        function displayNavSearchResults(results) {
            navSearchList.empty();
            
            if (results.length === 0) {
                navSearchList.html(`
                    <div class="dropdown-item text-muted">
                        <i class="bi bi-search"></i> No results found
                    </div>
                `);
            } else {
                results.forEach(function(result) {
                    const icon = result.type === 'course' ? 
                        '<i class="bi bi-book text-primary"></i>' : 
                        '<i class="bi bi-file-earmark text-success"></i>';
                    
                    const item = `
                        <a href="${result.url}" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div class="me-2">${icon}</div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">${result.title}</div>
                                    <small class="text-muted">${result.description}</small>
                                </div>
                            </div>
                        </a>
                    `;
                    navSearchList.append(item);
                });
                
                // Add "View all results" link
                navSearchList.append(`
                    <div class="dropdown-divider"></div>
                    <a href="/search?q=${encodeURIComponent(navSearchInput.val().trim())}" class="dropdown-item text-center">
                        <small>View all results</small>
                    </a>
                `);
            }
            
            navSearchResults.show();
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>
