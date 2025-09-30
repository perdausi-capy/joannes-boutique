<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();
            
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = 'Please fill in all fields';
            } else {
                $user = $this->userModel->authenticate($email, $password);
                if ($user) {
                    Auth::login($user);

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        $redirect = 'admin/dashboard';
                    } else {
                        $redirect = $_GET['redirect'] ?? 'auth/profile';
                        if (str_starts_with($redirect, '/')) {
                            $redirect = ltrim($redirect, '/');
                        }
                    }
                    header('Location: ' . BASE_URL . $redirect);
                    exit;
                } else {
                    $error = 'Invalid email or password';
                }
            }
        }
        
        $this->render('auth/login', ['error' => $error ?? null]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();
            
            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'phone' => trim($_POST['phone'] ?? '')
            ];
            
            $errors = $this->validateRegistration($data);
            
            if (empty($errors)) {
                try {
                    $userId = $this->userModel->register($data);
                    $user = $this->userModel->findById($userId);
                    Auth::login($user);
                    
                    header('Location: ' . BASE_URL . 'auth/profile');
                    exit;
                } catch (Exception $e) {
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $errors[] = 'Email address is already registered';
                    } else {
                        $errors[] = 'Registration failed. Please try again.';
                    }
                }
            }
        }
        
        $this->render('auth/register', [
            'errors' => $errors ?? [],
            'data' => $data ?? []
        ]);
    }
    
    private function validateRegistration($data) {
        $errors = [];
        
        if (empty($data['first_name'])) {
            $errors[] = 'First name is required';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Last name is required';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }
        
        if (empty($data['password'])) {
            $errors[] = 'Password is required';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Passwords do not match';
        }
        
        return $errors;
    }
    
    public function logout() {
        Auth::logout();
        header('Location: ' . BASE_URL);
        exit;
    }
    
    public function profile() {
        Auth::requireLogin();

        $userId = $_SESSION['user_id'];
        $orderModel = new Order();
        $bookingModel = new Booking();
        $testimonialModel = new Testimonial();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::requireToken();
            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? '')
            ];
            if (!empty($data['first_name']) && !empty($data['last_name'])) {
                $this->userModel->update($userId, $data);
                $success = 'Profile updated successfully!';
            } else {
                $error = 'First name and last name are required';
            }
        }

        $user = Auth::user();
        $orders = $orderModel->getUserOrders($userId);
        $bookings = method_exists($bookingModel, 'findByUserId') ? $bookingModel->findByUserId($userId) : [];
        $testimonials = method_exists($testimonialModel, 'findByUserId') ? $testimonialModel->findByUserId($userId) : [];

        $this->render('auth/profile', [
            'user' => $user,
            'orders' => $orders,
            'bookings' => $bookings,
            'testimonials' => $testimonials,
            'success' => $success ?? null,
            'error' => $error ?? null,
            'pw_success' => $_SESSION['pw_success'] ?? null,
            'pw_error' => $_SESSION['pw_error'] ?? null
        ]);
        unset($_SESSION['pw_success'], $_SESSION['pw_error']);
    }

    public function changePassword() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/profile');
            exit;
        }
        CSRF::requireToken();
        $user = Auth::user();
        $oldPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['pw_error'] = 'All password fields are required.';
        } elseif (!password_verify($oldPassword, $user['password'])) {
            $_SESSION['pw_error'] = 'Current password is incorrect.';
        } elseif (strlen($newPassword) < 6) {
            $_SESSION['pw_error'] = 'New password must be at least 6 characters.';
        } elseif ($newPassword !== $confirmPassword) {
            $_SESSION['pw_error'] = 'New passwords do not match.';
        } else {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->userModel->update($user['id'], ['password' => $hashed]);
            $_SESSION['pw_success'] = 'Password changed successfully!';
        }
        header('Location: /auth/profile');
        exit;
    }
    
    public function orders() {
        Auth::requireLogin();
        
        $orderModel = new Order();
        $orders = $orderModel->getUserOrders($_SESSION['user_id']);
        
        $this->render('auth/orders', ['orders' => $orders]);
    }
    
    private function render($template, $data = []) {
        extract($data);
        $pageTitle = ucfirst(str_replace(['auth/', '/'], ['', ' - '], $template)) . ' | Joanne\'s';
        
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        
        include $viewsDir . '/layout.php';
    }
}


