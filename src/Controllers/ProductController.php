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
        
        // Check if this is an AJAX request
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'products' => $products,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total' => $totalProducts,
                    'per_page' => $limit
                ]
            ]);
            exit;
        }
        
        // Regular page render
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
        
        // Load extra images
        require_once __DIR__ . '/../Models/ProductImage.php';
        $imageModel = new ProductImage();
        $productImages = $imageModel->findByProductId($id);
        
        // Get related products
        $relatedProducts = [];
        if (!empty($product['category_id'])) {
            $relatedProducts = $this->productModel->findByCategory($product['category_id'], 4);
            $relatedProducts = array_filter($relatedProducts, fn($p) => $p['id'] != $id);
        }
        
        // Calculate penalty information for overdue rentals of this product
        require_once __DIR__ . '/../Models/BookingOrder.php';
        require_once __DIR__ . '/../Utils/Auth.php';
        
        $bookingOrderModel = new BookingOrder();
        $penaltyInfo = [
            'has_overdue' => false,
            'overdue_rentals' => [],
            'total_penalty' => 0,
            'user_overdue_order' => null
        ];
        
        // Get overdue rentals for this product
        $userId = Auth::isLoggedIn() ? $_SESSION['user_id'] : null;
        $overdueRentals = $bookingOrderModel->getOverdueRentalsForItem($id, $userId);
        
        if (!empty($overdueRentals)) {
            $penaltyInfo['has_overdue'] = true;
            $penaltyInfo['overdue_rentals'] = $overdueRentals;
            
            // Calculate total penalty for current user (if logged in)
            if ($userId) {
                foreach ($overdueRentals as $rental) {
                    if ($rental['user_id'] == $userId && 
                        (empty($rental['is_penalty_paid']) || $rental['is_penalty_paid'] == 0)) {
                        $penaltyInfo['total_penalty'] += $rental['current_penalty'];
                        // Store the most recent overdue order for the user
                        if (!$penaltyInfo['user_overdue_order']) {
                            $penaltyInfo['user_overdue_order'] = $rental;
                        }
                    }
                }
            }
        }
        
        // Check if there's an order_id in URL (for payment display)
        $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;
        $currentOrder = null;
        $orderPenaltyInfo = null;
        
        if ($orderId) {
            $currentOrder = $bookingOrderModel->getOrderWithDetails($orderId);
            
            // Verify order belongs to this product and current user
            if ($currentOrder && 
                $currentOrder['item_id'] == $id && 
                $currentOrder['order_type'] == 'rental') {
                
                // Calculate penalty for this specific order
                $orderPenaltyInfo = $bookingOrderModel->calculatePenalty($orderId);
                
                // Auto-update penalty in database
                if ($orderPenaltyInfo['is_overdue']) {
                    $bookingOrderModel->calculateAndUpdatePenalty($orderId);
                    // Refresh order data
                    $currentOrder = $bookingOrderModel->getOrderWithDetails($orderId);
                }
                
                // Get total amount due (rental + penalty if unpaid)
                $totalDue = $bookingOrderModel->getTotalAmountDue($orderId);
                $orderPenaltyInfo['total_due'] = $totalDue;
            } else {
                $currentOrder = null; // Invalid order for this product
            }
        }
        
        $this->render('products/show', [
            'product' => $product,
            'productImages' => $productImages,
            'relatedProducts' => $relatedProducts,
            'penaltyInfo' => $penaltyInfo,
            'currentOrder' => $currentOrder,
            'orderPenaltyInfo' => $orderPenaltyInfo
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