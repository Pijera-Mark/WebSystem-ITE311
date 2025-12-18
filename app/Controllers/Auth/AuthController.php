<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    // Load cookie helper for remember me functionality
    protected $helpers = ['cookie'];
    
    // Rate limiting configuration
    private $maxLoginAttempts = 5;
    private $lockoutDuration = 900; // 15 minutes
    
    /**
     * Display and process registration form
     */
    public function register()
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        // Check if registration is allowed
        if (!$this->isRegistrationAllowed()) {
            session()->setFlashdata('error', 'Registration is currently disabled');
            return redirect()->to('/login');
        }
        
        // Check rate limiting
        if ($this->isRateLimited('register', session()->get('ip_address'))) {
            session()->setFlashdata('error', 'Too many registration attempts. Please try again later.');
            return redirect()->to('/register');
        }
        
        // Check if form was submitted
        if ($this->request->getMethod() === 'POST') {
            // CSRF token validation
            $csrfToken = $this->request->getPost('csrf_token');
            if (!$csrfToken || $csrfToken !== session()->get('csrf_token')) {
                session()->setFlashdata('error', 'Invalid request. Please try again.');
                return redirect()->to('/register');
            }
            
            // Enhanced validation rules
            $rules = [
                'name' => [
                    'rules' => 'required|min_length[3]|max_length[100]|alpha_space',
                    'errors' => [
                        'required' => 'Name is required',
                        'min_length' => 'Name must be at least 3 characters long',
                        'max_length' => 'Name cannot exceed 100 characters',
                        'alpha_space' => 'Name can only contain letters and spaces'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[users.email]|max_length[191]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please enter a valid email address',
                        'is_unique' => 'This email is already registered',
                        'max_length' => 'Email cannot exceed 191 characters'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 8 characters long',
                        'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character'
                    ]
                ],
                'password_confirm' => [
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Please confirm your password',
                        'matches' => 'Passwords do not match'
                    ]
                ]
            ];

            // Validate form data
            if (!$this->validate($rules)) {
                // Log failed registration attempt
                $this->logSecurityEvent('registration_failed', [
                    'email' => $this->request->getPost('email'),
                    'ip' => $this->request->getIPAddress(),
                    'errors' => $this->validator->getErrors()
                ]);
                
                return view('auth/register', [
                    'validation' => $this->validator,
                    'csrf_token' => $this->generateCSRFToken()
                ]);
            }

            // Sanitize input data
            $name = $this->sanitizeInput($this->request->getPost('name'));
            $email = filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL);
            
            // Verify email after sanitization
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Invalid email format');
                return view('auth/register', [
                    'csrf_token' => $this->generateCSRFToken()
                ]);
            }

            // Hash the password with secure algorithm
            $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_ARGON2ID);

            // Prepare user data
            $userData = [
                'name' => $name,
                'email' => $email,
                'password_hash' => $hashedPassword,
                'role' => 'student', // Default role for security
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Save to database with transaction
            $db = \Config\Database::connect();
            $db->transStart();
            
            try {
                $builder = $db->table('users');
                
                if ($builder->insert($userData)) {
                    $db->transComplete();
                    
                    // Log successful registration
                    $this->logSecurityEvent('registration_success', [
                        'email' => $email,
                        'ip' => $this->request->getIPAddress()
                    ]);
                    
                    // Set success message
                    session()->setFlashdata('success', 'Registration successful! Please login with your credentials.');
                    return redirect()->to('/login');
                } else {
                    $db->transRollback();
                    session()->setFlashdata('error', 'Registration failed. Please try again.');
                }
            } catch (\Exception $e) {
                $db->transRollback();
                log_message('error', 'Registration error: ' . $e->getMessage());
                session()->setFlashdata('error', 'Registration failed. Please try again.');
            }
        }

        // Generate CSRF token for form
        $csrfToken = $this->generateCSRFToken();
        
        return view('auth/register', [
            'csrf_token' => $csrfToken
        ]);
    }

    /**
     * Secure login method with rate limiting and brute force protection
     */
    public function login()
    {
        // Check if user is already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // Get client IP for rate limiting
        $ip = $this->request->getIPAddress();
        
        // Check rate limiting
        if ($this->isRateLimited('login', $ip)) {
            session()->setFlashdata('error', 'Too many login attempts. Please try again in 15 minutes.');
            return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
        }

        // Check if form was submitted
        if ($this->request->getMethod() === 'POST') {
            // CSRF token validation
            $csrfToken = $this->request->getPost('csrf_token');
            if (!$csrfToken || $csrfToken !== session()->get('csrf_token')) {
                session()->setFlashdata('error', 'Invalid request. Please try again.');
                return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
            }
            
            // Get and sanitize input
            $email = filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL);
            $password = $this->request->getPost('password');
            
            // Basic validation
            if (empty($email) || empty($password)) {
                session()->setFlashdata('error', 'Email and password are required');
                $this->incrementRateLimit('login', $ip);
                return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
            }
            
            // Verify email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Invalid email format');
                $this->incrementRateLimit('login', $ip);
                return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
            }

            try {
                // Get user from database
                $db = \Config\Database::connect();
                $builder = $db->table('users');
                $user = $builder->where('email', $email)->get()->getRowArray();

                // Check if user exists and verify password
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Check if account is locked (optional feature)
                    if ($this->isAccountLocked($user['id'])) {
                        session()->setFlashdata('error', 'Account is temporarily locked. Please try again later.');
                        return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
                    }
                    
                    // Rehash password if needed (for security upgrades)
                    if (password_needs_rehash($user['password_hash'], PASSWORD_ARGON2ID)) {
                        $newHash = password_hash($password, PASSWORD_ARGON2ID);
                        $builder->where('id', $user['id'])->update(['password_hash' => $newHash]);
                    }
                    
                    // Create secure session data
                    $sessionData = [
                        'userID' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                        'ip_address' => $ip,
                        'user_agent' => $this->request->getUserAgent(),
                        'last_activity' => time()
                    ];

                    session()->set($sessionData);
                    session()->regenerate(true); // Prevent session fixation

                    // Handle remember me securely
                    if ($this->request->getPost('remember')) {
                        $this->createRememberToken($user['id']);
                    }

                    // Log successful login
                    $this->logSecurityEvent('login_success', [
                        'user_id' => $user['id'],
                        'email' => $email,
                        'ip' => $ip,
                        'user_agent' => $this->request->getUserAgent()
                    ]);
                    
                    // Clear failed login attempts
                    $this->clearRateLimit('login', $ip);
                    
                    session()->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
                    return redirect()->to('/dashboard');
                } else {
                    // Log failed login attempt
                    $this->logSecurityEvent('login_failed', [
                        'email' => $email,
                        'ip' => $ip,
                        'user_agent' => $this->request->getUserAgent(),
                        'reason' => $user ? 'invalid_password' : 'user_not_found'
                    ]);
                    
                    // Increment rate limit counter
                    $this->incrementRateLimit('login', $ip);
                    
                    // Generic error message (don't reveal if user exists)
                    session()->setFlashdata('error', 'Invalid email or password');
                }
            } catch (\Exception $e) {
                log_message('error', 'Login error: ' . $e->getMessage());
                session()->setFlashdata('error', 'Login failed. Please try again.');
                $this->incrementRateLimit('login', $ip);
            }

            return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
        }

        // Display login form with CSRF token
        return view('auth/login', ['csrf_token' => $this->generateCSRFToken()]);
    }

    /**
     * Logout user and destroy session
     */
    public function logout()
    {
        $userId = session()->get('userID');
        if ($userId) {
            $this->deleteRememberToken($userId);
        }

        // Destroy session
        session()->destroy();
        
        // Clear remember cookie
        helper('cookie');
        set_cookie('remember_token', '', time() - 3600, '', '', '', true, true);
        
        // Set logout message
        session()->setFlashdata('success', 'You have been logged out successfully');
        return redirect()->to('/login');
    }

    /**
     * Debug endpoint to show session and database status
     */
    public function debug()
    {
        log_message('debug', 'Auth::debug() called');
        $db = \Config\Database::connect();
        $users = $db->table('users')->get()->getResultArray();
        $session = session()->get();

        $output = "<h2>Session</h2><pre>" . json_encode($session, JSON_PRETTY_PRINT) . "</pre>";
        $output .= "<h2>Users in DB</h2><pre>" . json_encode($users, JSON_PRETTY_PRINT) . "</pre>";
        $output .= "<h2>Request Method</h2><p>" . $this->request->getMethod() . "</p>";
        return $output;
    }

    /**
     * Protected dashboard page with role-based content
     */
    public function dashboard()
    {
        log_message('debug', '=== dashboard() start ===');
        log_message('debug', 'Session raw: ' . json_encode(session()->get()));
        log_message('debug', 'Session isLoggedIn: ' . json_encode(session()->get('isLoggedIn')));

        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            log_message('debug', 'Not logged in, redirecting to /login');
            session()->setFlashdata('error', 'Please login to access the dashboard');
            return redirect()->to('/login');
        }

        log_message('debug', 'Passed login check');

        // Get user role from session
        $userRole = session()->get('role');
        log_message('debug', 'User role: ' . $userRole);
        
        log_message('debug', 'About to call getRoleSpecificData');
        try {
            // Fetch role-specific data
            $roleData = $this->getRoleSpecificData($userRole);
            log_message('debug', 'getRoleSpecificData succeeded');
        } catch (\Exception $e) {
            log_message('error', 'Dashboard data fetch failed: ' . $e->getMessage());
            log_message('debug', 'Exception details: ' . $e->getTraceAsString());
            $roleData = [];
        }

        log_message('debug', 'About to load view');
        // Display dashboard with role-specific data
        // Fallback: return raw HTML if view fails
        try {
            $output = view('auth/dashboard', [
                'userRole' => $userRole,
                'roleData' => $roleData
            ]);
            log_message('debug', 'View loaded successfully');
            return $output;
        } catch (\Exception $e) {
            log_message('error', 'Dashboard view failed: ' . $e->getMessage());
            $name = session()->get('name');
            $html = "<h1>Welcome, $name</h1><p>Role: $userRole</p><p>Dashboard view could not be loaded.</p>";
            log_message('debug', 'Returning fallback HTML');
            return $html;
        }
    }

    /**
     * Log failed login attempt
     */
    private function logFailedLogin($email)
    {
        $ip = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent();
        $timestamp = date('Y-m-d H:i:s');

        $logEntry = sprintf(
            "[%s] Failed login attempt for email: %s | IP: %s | User-Agent: %s\n",
            $timestamp,
            $email,
            $ip,
            $userAgent
        );

        log_message('error', 'Failed login attempt: ' . $logEntry);
    }

    /**
     * Create a secure remember token
     */
    private function createRememberToken($userId)
    {
        $db = \Config\Database::connect();
        
        // Delete any existing tokens for this user
        $db->table('remember_tokens')->where('user_id', $userId)->delete();
        
        // Generate a random token
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $tokenData = [
            'user_id' => $userId,
            'selector' => $selector,
            'validator' => $hashedValidator,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('remember_tokens')->insert($tokenData);
        
        // Set cookie with selector:validator (not hashed)
        $cookieValue = $selector . ':' . $validator;
        helper('cookie');
        set_cookie('remember_token', $cookieValue, 30 * 86400, '', '', '', true, true);
    }

    /**
     * Delete remember token for user
     */
    private function deleteRememberToken($userId)
    {
        $db = \Config\Database::connect();
        $db->table('remember_tokens')->where('user_id', $userId)->delete();
    }

    /**
     * Validate remember token and auto-login
     */
    public function validateRememberToken()
    {
        helper('cookie');
        $cookie = get_cookie('remember_token');
        if (!$cookie) {
            return false;
        }
        
        list($selector, $validator) = explode(':', $cookie, 2);
        
        $db = \Config\Database::connect();
        $token = $db->table('remember_tokens')
            ->where('selector', $selector)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRowArray();
            
        if (!$token) {
            return false;
        }
        
        if (!hash_equals($token['validator'], hash('sha256', $validator))) {
            return false;
        }
        
        // Get user
        $user = $db->table('users')
            ->where('id', $token['user_id'])
            ->get()
            ->getRowArray();
            
        if (!$user) {
            return false;
        }
        
        // Create session
        $session = session();
        $sessionData = [
            'userID' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true
        ];
        
        $session->set($sessionData);
        $session->regenerate(true);
        
        // Rotate token
        $this->createRememberToken($user['id']);
        
        return true;
    }

    /**
     * Get role-specific data for dashboard
     */
    private function getRoleSpecificData($role)
    {
        $db = \Config\Database::connect();
        
        switch ($role) {
            case 'admin':
                try {
                    // Admin data: total users, courses, enrollments
                    $totalUsers = $db->table('users')->countAll();
                    $totalCourses = $db->table('courses')->countAll();
                    $totalEnrollments = $db->table('enrollments')->countAll();
                    
                    return [
                        'totalUsers' => $totalUsers,
                        'totalCourses' => $totalCourses,
                        'totalEnrollments' => $totalEnrollments,
                        'recentUsers' => $db->table('users')
                            ->orderBy('created_at', 'DESC')
                            ->limit(5)
                            ->get()
                            ->getResultArray()
                    ];
                } catch (\Exception $e) {
                    log_message('error', 'Database error in admin dashboard: ' . $e->getMessage());
                    return [
                        'totalUsers' => 0,
                        'totalCourses' => 0,
                        'totalEnrollments' => 0,
                        'recentUsers' => []
                    ];
                }
                
            case 'instructor':
            case 'teacher':
                // Instructor data: their courses, student count
                $instructorId = session()->get('userID');
                $instructorCourses = $db->table('courses')
                    ->where('instructor_id', $instructorId)
                    ->get()
                    ->getResultArray();
                
                $totalStudents = $db->table('enrollments')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('courses.instructor_id', $instructorId)
                    ->countAllResults();
                
                return [
                    'courses' => $instructorCourses,
                    'totalStudents' => $totalStudents,
                    'totalCourses' => count($instructorCourses)
                ];
                
            case 'student':
            default:
                // Student data: enrolled courses, progress
                $studentId = session()->get('userID');
                $enrolledCourses = $db->table('enrollments')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $studentId)
                    ->get()
                    ->getResultArray();
                
                // Get available courses (courses not enrolled in)
                $enrolledCourseIds = [];
                foreach ($enrolledCourses as $course) {
                    $enrolledCourseIds[] = $course['course_id'];
                }
                
                $availableCourses = [];
                if (!empty($enrolledCourseIds)) {
                    $availableCourses = $db->table('courses')
                        ->whereNotIn('id', $enrolledCourseIds)
                        ->get()
                        ->getResultArray();
                } else {
                    $availableCourses = $db->table('courses')
                        ->get()
                        ->getResultArray();
                }
                
                return [
                    'enrolledCourses' => $enrolledCourses,
                    'availableCourses' => $availableCourses,
                    'totalCourses' => count($enrolledCourses)
                ];
        }
    }
    
    /**
     * Generate CSRF token
     */
    private function generateCSRFToken()
    {
        $token = bin2hex(random_bytes(32));
        session()->set('csrf_token', $token);
        return $token;
    }
    
    /**
     * Sanitize input data
     */
    private function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Check if registration is allowed
     */
    private function isRegistrationAllowed()
    {
        // You can implement logic to check if registration is enabled
        // For example, check a settings table or config file
        return true; // Allow registration for now
    }
    
    /**
     * Check rate limiting
     */
    private function isRateLimited($action, $identifier)
    {
        $cacheKey = "rate_limit_{$action}_{$identifier}";
        $attempts = cache()->get($cacheKey) ?: 0;
        
        if ($attempts >= $this->maxLoginAttempts) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Increment rate limit counter
     */
    private function incrementRateLimit($action, $identifier)
    {
        $cacheKey = "rate_limit_{$action}_{$identifier}";
        $attempts = cache()->get($cacheKey) ?: 0;
        cache()->save($cacheKey, $attempts + 1, $this->lockoutDuration);
    }
    
    /**
     * Clear rate limit counter
     */
    private function clearRateLimit($action, $identifier)
    {
        $cacheKey = "rate_limit_{$action}_{$identifier}";
        cache()->delete($cacheKey);
    }
    
    /**
     * Check if account is locked
     */
    private function isAccountLocked($userId)
    {
        // You can implement account locking logic here
        // For example, check a locked_accounts table or user status
        return false; // No account locking for now
    }
    
    /**
     * Log security events
     */
    private function logSecurityEvent($event, $data = [])
    {
        $logData = [
            'event' => $event,
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $data
        ];
        
        log_message('info', 'Security Event: ' . json_encode($logData));
    }
}
