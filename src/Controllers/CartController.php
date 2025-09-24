<?php
class CartController {
    private $cartModel;
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();
        
        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        
        $this->render('cart/index', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ]);
    }
    
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        
        CSRF::requireToken();
        
        $productId = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($productId <= 0 || $quantity <= 0) {
            return json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
        }
        
        $product = $this->productModel->findById($productId);
        if (!$product) {
            return json_encode(['success' => false, 'message' => 'Product not found']);
        }
        
        if ($product['stock_quantity'] < $quantity) {
            return json_encode(['success' => false, 'message' => 'Insufficient stock']);
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();
        
        try {
            $this->cartModel->addItem($userId, $sessionId, $productId, $quantity);
            $cartCount = $this->cartModel->getCartCount($userId, $sessionId);
            
            return json_encode([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => $cartCount
            ]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
        }
    }
    
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        
        CSRF::requireToken();
        
        $itemId = intval($_POST['item_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($quantity <= 0) {
            return $this->removeFromCart();
        }
        
        try {
            $this->cartModel->update($itemId, ['quantity' => $quantity]);
            return json_encode(['success' => true, 'message' => 'Cart updated']);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Failed to update cart']);
        }
    }
    
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
        
        CSRF::requireToken();
        
        $itemId = intval($_POST['item_id'] ?? 0);
        
        try {
            $this->cartModel->delete($itemId);
            return json_encode(['success' => true, 'message' => 'Item removed from cart']);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Failed to remove item']);
        }
    }
    
    public function getCartCount() {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();
        
        $count = $this->cartModel->getCartCount($userId, $sessionId);
        return json_encode(['count' => $count]);
    }
    
    public function checkout() {
        Auth::requireLogin();
        
        $userId = $_SESSION['user_id'];
        $sessionId = session_id();
        
        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        
        if (empty($cartItems)) {
            header('Location: /cart');
            exit;
        }
        
        $user = Auth::user();
        
        $this->render('cart/checkout', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'user' => $user
        ]);
    }
    
    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart/checkout');
            exit;
        }
        
        CSRF::requireToken();
        Auth::requireLogin();
        
        $userId = $_SESSION['user_id'];
        $sessionId = session_id();
        
        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        
        if (empty($cartItems)) {
            header('Location: /cart');
            exit;
        }
        
        $orderData = [
            'shipping_address' => trim($_POST['shipping_address'] ?? ''),
            'billing_address' => trim($_POST['billing_address'] ?? ''),
            'payment_method' => $_POST['payment_method'] ?? 'bank_transfer',
            'notes' => trim($_POST['notes'] ?? '')
        ];
        
        // Validate required fields
        if (empty($orderData['shipping_address'])) {
            $error = 'Shipping address is required';
            $this->render('cart/checkout', [
                'cartItems' => $cartItems,
                'cartTotal' => $this->cartModel->getCartTotal($userId, $sessionId),
                'user' => Auth::user(),
                'error' => $error,
                'orderData' => $orderData
            ]);
            return;
        }
        
        try {
            $orderId = $this->orderModel->createOrder($userId, $cartItems, $orderData);
            $this->cartModel->clearCart($userId, $sessionId);
            
            header("Location: /auth/orders?success=Order placed successfully!");
            exit;
        } catch (Exception $e) {
            $error = 'Failed to process order. Please try again.';
            $this->render('cart/checkout', [
                'cartItems' => $cartItems,
                'cartTotal' => $this->cartModel->getCartTotal($userId, $sessionId),
                'user' => Auth::user(),
                'error' => $error,
                'orderData' => $orderData
            ]);
        }
    }
    
    private function render($template, $data = []) {
        extract($data);
        $pageTitle = 'Shopping Cart | Joanne\'s';
        
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        
        include $viewsDir . '/layout.php';
    }
}