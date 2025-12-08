<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Authentication Routes
$routes->get('/register', 'Auth\AuthController::register');
$routes->post('/register', 'Auth\AuthController::register');
$routes->get('/login', 'Auth\AuthController::login');
$routes->post('/login', 'Auth\AuthController::login');
$routes->get('/logout', 'Auth\AuthController::logout');
$routes->get('/dashboard', 'Auth\AuthController::dashboard');

// Test route to confirm routing works mathematical
$routes->get('test', function() { return 'Routing works'; });

// Test POST endpoint
$routes->post('test-post', function() { return 'POST request works'; });

// Debug route
$routes->get('debug', 'Auth::debug');

// Admin Routes
$routes->group('/admin', function($routes) {
    $routes->get('users', 'Admin\AdminController::users');
    $routes->get('courses', 'Admin\AdminController::courses');
    $routes->get('reports', 'Admin\AdminController::reports');
    $routes->get('settings', 'Admin\AdminController::settings');
    $routes->get('create-user', 'Admin\AdminController::createUser');
    $routes->post('create-user', 'Admin\AdminController::createUser');
    $routes->get('edit-user/(:num)', 'Admin\AdminController::editUser/$1');
    $routes->post('edit-user/(:num)', 'Admin\AdminController::editUser/$1');
    $routes->get('delete-user/(:num)', 'Admin\AdminController::deleteUser/$1');
});

// Instructor Routes
$routes->group('/instructor', function($routes) {
    $routes->get('create-course', 'Instructor\InstructorController::createCourse');
    $routes->post('create-course', 'Instructor\InstructorController::createCourse');
    $routes->get('my-courses', 'Instructor\InstructorController::myCourses');
    $routes->get('course/(:num)', 'Instructor\InstructorController::viewCourse/$1');
    $routes->get('edit-course/(:num)', 'Instructor\InstructorController::editCourse/$1');
    $routes->post('edit-course/(:num)', 'Instructor\InstructorController::editCourse/$1');
    $routes->get('course-analytics/(:num)', 'Instructor\InstructorController::courseAnalytics/$1');
    $routes->get('grade-submissions', 'Instructor\InstructorController::gradeSubmissions');
    $routes->get('manage-students', 'Instructor\InstructorController::manageStudents');
    $routes->get('reports', 'Instructor\InstructorController::reports');
});

// Student Routes
$routes->group('/student', function($routes) {
    $routes->get('dashboard', 'Student\StudentController::dashboard');
    $routes->get('my-courses', 'Student\StudentController::myCourses');
    $routes->get('course/(:num)', 'Student\StudentController::viewCourse/$1');
    $routes->get('course-progress/(:num)', 'Student\StudentController::courseProgress/$1');
    $routes->get('certificates', 'Student\StudentController::certificates');
    $routes->get('profile', 'Student\StudentController::profile');
    $routes->post('update-progress', 'Student\StudentController::updateProgress');
});

// Course Routes (public)
$routes->get('courses/browse', 'CourseController::browse');
$routes->get('courses/(:num)', 'CourseController::view/$1');
