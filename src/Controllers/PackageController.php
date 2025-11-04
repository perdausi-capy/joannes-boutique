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
    
    public function index() {
        // Show dynamic packages stored in DB
        $packages = $this->packageModel->findAll();
        $this->render('packages/index', [ 'packages' => $packages ]);
    }

    public function show(int $id) {
        $package = $this->packageModel->findById($id);
        if (!$package) { http_response_code(404); echo 'Package not found'; return; }
        $this->render('packages/show', [ 'package' => $package ]);
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
