<?php
class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $categorySlug = $_GET['category'] ?? '';
        $searchTerm = trim($_GET['search'] ?? '');
        
        if ($searchTerm) {
            $products = $this->productModel->search($searchTerm, $limit);
            $totalProducts = count($products);
        } elseif ($categorySlug) {
            $category = $this->categoryModel->findBySlug($categorySlug);
            if ($category) {
                $products = $this->productModel->findByCategory($category['id'], $limit);
                $totalProducts = count($products);
            } else {
                $products = [];
                $totalProducts = 0;
            }
        } else {
            $products = $this->productModel->findAll($limit, $offset);
            $totalProducts = $this->productModel->count();
        }
        
        $categories = $this->categoryModel->findAll();
        $totalPages = ceil($totalProducts / $limit);
        
        $this->render('products/index', [
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'searchTerm' => $searchTerm,
            'currentCategory' => $categorySlug
        ]);
    }
    
    public function show($id) {
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            http_response_code(404);
            $this->render('404');
            return;
        }
        
        // Get related products
        $relatedProducts = [];
        if (!empty($product['category_id'])) {
            $relatedProducts = $this->productModel->findByCategory($product['category_id'], 4);
            // Remove current product from related
            $relatedProducts = array_filter($relatedProducts, fn($p) => $p['id'] != $id);
        }
        
        $this->render('products/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
    
    public function searchAPI() {
        $searchTerm = trim($_GET['q'] ?? '');
        $results = [];
        
        if (strlen($searchTerm) >= 2) {
            $results = $this->productModel->search($searchTerm, 10);
        }
        
        return json_encode(['results' => $results]);
    }
    
    private function render($template, $data = []) {
        extract($data);
        $pageTitle = 'Products | Joanne\'s';
        
        if (isset($product)) {
            $pageTitle = $product['name'] . ' | Joanne\'s';
        }
        
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        
        include $viewsDir . '/layout.php';
    }
}


