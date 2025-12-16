<?php
class PackageController {
    private $productModel;
    private $categoryModel;
    private $packageModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        require_once __DIR__ . '/../Models/Package.php';
        $this->packageModel = new Package();
    }
    
    public function index()
    {
        // ✅ USE $this->packageModel instead of creating new instance
        $packages = $this->packageModel->findAllWithReservationStatus();
        
        $data = [
            'packages' => $packages
            
        ];
        
        $this->render('packages/index', $data);
    }
    
    public function show($id)
    {
        // ✅ USE $this->packageModel instead of creating new instance
        $package = $this->packageModel->findByIdWithReservationStatus($id);
        
        if (!$package) {
            $_SESSION['error'] = 'Package not found';
            header('Location: ' . BASE_URL . 'packages');
            exit;
        }
        
        $data = [
            'package' => $package
        ];
        
        $this->render('packages/show', $data);
    }
    
    private function render($template, $data = []) {
        extract($data);
        $pageTitle = 'Packages | Joanne\'s';
        
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        
        include $viewsDir . '/home.php';
    }
}