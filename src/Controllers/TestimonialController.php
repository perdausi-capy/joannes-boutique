<?php
class TestimonialController {
    private $testimonialModel;
    
    public function __construct() {
        require_once __DIR__ . '/../Models/Testimonial.php';
        $this->testimonialModel = new Testimonial();
    }
    
    public function index() {
        $testimonials = $this->testimonialModel->findPublic();
        
        $this->render('testimonials/index', [
            'testimonials' => $testimonials
        ]);
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . rtrim(BASE_URL, '/') . '/testimonials');
            exit;
        }
        
        CSRF::requireToken();
        
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'rating' => (int)$_POST['rating'] ?? 5,
            'message' => trim($_POST['message'] ?? ''),
            'status' => 'pending'
        ];
        
        // Basic validation
        if (empty($data['name']) || empty($data['message'])) {
            $_SESSION['testimonial_message'] = ['type' => 'error', 'text' => 'Please fill in all required fields.'];
            header('Location: ' . rtrim(BASE_URL, '/') . '/testimonials');
            exit;
        }
        
        try {
            $this->testimonialModel->create($data);
            $_SESSION['testimonial_message'] = ['type' => 'success', 'text' => 'Thank you for your review! It will be published after moderation.'];
            header('Location: ' . rtrim(BASE_URL, '/') . '/testimonials');
            exit;
        } catch (Exception $e) {
            $_SESSION['testimonial_message'] = ['type' => 'error', 'text' => 'Failed to submit review. Please try again.'];
            header('Location: ' . rtrim(BASE_URL, '/') . '/testimonials');
            exit;
        }
    }
    
    private function render($template, $data = []) {
        extract($data);
        $pageTitle = 'Customer Reviews | Joanne\'s';
        
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        include $viewsDir . '/layout.php';
    }
}
