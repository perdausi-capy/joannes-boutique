<?php
class ContactController {
    
    public function index() {
        $message = $_SESSION['contact_message'] ?? '';
        unset($_SESSION['contact_message']);
        
        $this->render('contact/index', [
            'message' => $message
        ]);
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . rtrim(BASE_URL, '/') . '/contact');
            exit;
        }
        
        $this->processContact();
    }
    
    private function processContact() {
        CSRF::requireToken();
        
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? '')
        ];
        
        // Basic validation
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            $_SESSION['contact_message'] = ['type' => 'error', 'text' => 'Please fill in all required fields.'];
            header('Location: ' . rtrim(BASE_URL, '/') . '/contact');
            exit;
        }
        
        try {
            require_once __DIR__ . '/../Models/ContactMessage.php';
            $contactModel = new ContactMessage();
            $contactModel->create($data);
            
            $_SESSION['contact_message'] = ['type' => 'success', 'text' => 'Thank you for your message! We will get back to you soon.'];
            header('Location: ' . rtrim(BASE_URL, '/') . '/contact');
            exit;
        } catch (Exception $e) {
            $_SESSION['contact_message'] = ['type' => 'error', 'text' => 'Failed to send message. Please try again.'];
            header('Location: ' . rtrim(BASE_URL, '/') . '/contact');
            exit;
        }
    }
    
    private function render($template, $data = []) {
        extract($data);
        $pageTitle = 'Contact Us | Joanne\'s';
        
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        include $viewsDir . '/layout.php';
    }
}
