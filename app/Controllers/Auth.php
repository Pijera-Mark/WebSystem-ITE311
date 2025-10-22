<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    /**
     * Display and process registration form
     */
    public function register()
    {
        // Check if form was submitted
        if ($this->request->getMethod() === 'post') {
            // Set validation rules
            $rules = [
                'name' => [
                    'rules' => 'required|min_length[3]|max_length[100]',
                    'errors' => [
                        'required' => 'Name is required',
                        'min_length' => 'Name must be at least 3 characters',
                        'max_length' => 'Name cannot exceed 100 characters'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please provide a valid email address',
                        'is_unique' => 'This email is already registered'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 6 characters'
                    ]
                ],
                'password_confirm' => [
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Password confirmation is required',
                        'matches' => 'Passwords do not match'
                    ]
                ]
            ];

            // Validate form data
            if (!$this->validate($rules)) {
                // Validation failed, return to form with errors
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }

            // Hash the password
            $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

            // Prepare user data
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password_hash' => $hashedPassword,
                'role' => 'student', // Default role
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Save to database
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            
            if ($builder->insert($userData)) {
                // Set success message
                session()->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to('/login');
            } else {
                // Set error message
                session()->setFlashdata('error', 'Registration failed. Please try again.');
            }
        }

        // Display registration form
        return view('auth/register');
    }

    /**
     * Display and process login form
     */
    public function login()
    {
        // Check if form was submitted
        if ($this->request->getMethod() === 'post') {
            // Set validation rules
            $rules = [
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please provide a valid email address'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required'
                    ]
                ]
            ];

            // Validate form data
            if (!$this->validate($rules)) {
                return view('auth/login', [
                    'validation' => $this->validator
                ]);
            }

            // Get user from database
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $user = $builder->where('email', $this->request->getPost('email'))->get()->getRowArray();

            // Check if user exists and verify password
            if ($user && password_verify($this->request->getPost('password'), $user['password_hash'])) {
                // Create session data
                $sessionData = [
                    'userID' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ];

                session()->set($sessionData);

                // Set welcome message
                session()->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
                return redirect()->to('/dashboard');
            } else {
                // Invalid credentials
                session()->setFlashdata('error', 'Invalid email or password');
                return view('auth/login');
            }
        }

        // Display login form
        return view('auth/login');
    }

    /**
     * Logout user and destroy session
     */
    public function logout()
    {
        // Destroy session
        session()->destroy();
        
        // Set logout message
        session()->setFlashdata('success', 'You have been logged out successfully');
        return redirect()->to('/login');
    }

    /**
     * Protected dashboard page
     */
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access the dashboard');
            return redirect()->to('/login');
        }

        // Display dashboard
        return view('auth/dashboard');
    }
}
