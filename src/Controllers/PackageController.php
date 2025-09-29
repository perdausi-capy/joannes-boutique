<?php
class PackageController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        // Get all categories to group products by package types
        $categories = $this->categoryModel->findAll();
        
        // Get featured products for each category
        $packages = [];
        foreach ($categories as $category) {
            $products = $this->productModel->findByCategory($category['id'], 6);
            if (!empty($products)) {
                $packages[] = [
                    'category' => $category,
                    'products' => $products
                ];
            }
        }
        
        $this->render('packages/index', [
            'packages' => $packages,
            'categories' => $categories
        ]);
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
