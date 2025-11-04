<?php
class AdminController {
    public function dashboard() {
        Auth::requireAdmin();
        $user = Auth::user();
        $this->render('admin/dashboard', ['user' => $user]);
    }

    public function products() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/Product.php';
        require_once __DIR__ . '/../Models/Category.php';
        $productModel = new Product();
        $categoryModel = new Category();
        $action = $_GET['action'] ?? '';
        $message = '';
        // Ensure 'Suits' and 'Gowns' categories exist
        $requiredCategories = [
            ['name' => 'Suits', 'slug' => 'suits', 'description' => 'Professional and formal suits'],
            ['name' => 'Gowns', 'slug' => 'gowns', 'description' => 'Elegant evening and formal gowns']
        ];
        foreach ($requiredCategories as $cat) {
            $existing = $categoryModel->findBySlug($cat['slug']);
            if (!$existing) {
                $categoryModel->create([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'],
                    'is_active' => 1
                ]);
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file upload (primary image)
            $imageFileName = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = UPLOAD_PATH;
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageFileName = uniqid('prod_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageFileName);
            }
            // Validate required fields
            $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
            $name = trim($_POST['name'] ?? '');
            $price = $_POST['price'] ?? '';
            $stockQuantity = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
            
            if ($action === 'create') {
                $errors = [];
                if (!$categoryId) $errors[] = 'Category is required';
                if ($name === '') $errors[] = 'Product name is required';
                if ($price === '' || $price <= 0) $errors[] = 'Valid price is required';
                if ($stockQuantity < 0) $errors[] = 'Stock quantity cannot be negative';
                
                if (!empty($errors)) {
                    $message = '<span class="text-red-600">Please fix the following errors:<br>• ' . implode('<br>• ', $errors) . '</span>';
                } else {
                    $data = [
                        'category_id' => $categoryId,
                        'name' => $name,
                        'description' => trim($_POST['description'] ?? ''),
                        'price' => (float)$price,
                        'image' => $imageFileName ?: '',
                        'stock_quantity' => $stockQuantity,
                        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                        'is_active' => isset($_POST['is_active']) ? 1 : 0,
                    ];
                    try {
                        $newId = $productModel->create($data);
                        // Save multiple images if provided
                        if (!empty($_FILES['images']['name'][0])) {
                            require_once __DIR__ . '/../Models/ProductImage.php';
                            $imageModel = new ProductImage();
                            $count = count($_FILES['images']['name']);
                            for ($i = 0; $i < $count; $i++) {
                                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                                    $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                                    $fname = uniqid('prod_extra_', true) . '.' . $ext;
                                    move_uploaded_file($_FILES['images']['tmp_name'][$i], $uploadDir . $fname);
                                    $imageModel->create([
                                        'product_id' => $newId,
                                        'filename' => $fname,
                                        'sort_order' => $i
                                    ]);
                                }
                            }
                        }
                        $message = '<span class="text-green-600">✓ Product created successfully!</span>';
                    } catch (PDOException $e) {
                        $message = '<span class="text-red-600">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                    }
                }
            } elseif ($action === 'edit' && isset($_POST['id'])) {
                $id = (int)$_POST['id'];
                $errors = [];
                if (!$categoryId) $errors[] = 'Category is required';
                if ($name === '') $errors[] = 'Product name is required';
                if ($price === '' || $price <= 0) $errors[] = 'Valid price is required';
                if ($stockQuantity < 0) $errors[] = 'Stock quantity cannot be negative';
                
                if (!empty($errors)) {
                    $message = '<span class="text-red-600">Please fix the following errors:<br>• ' . implode('<br>• ', $errors) . '</span>';
                } else {
                    $data = [
                        'category_id' => $categoryId,
                        'name' => $name,
                        'description' => trim($_POST['description'] ?? ''),
                        'price' => (float)$price,
                        'stock_quantity' => $stockQuantity,
                        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                        'is_active' => isset($_POST['is_active']) ? 1 : 0,
                    ];
                    if ($imageFileName) {
                        $data['image'] = $imageFileName;
                    } elseif (!empty($_POST['existing_image'])) {
                        $data['image'] = $_POST['existing_image'];
                    }
                    try {
                        $productModel->update($id, $data);
                        // Save additional uploaded images
                        if (!empty($_FILES['images']['name'][0])) {
                            require_once __DIR__ . '/../Models/ProductImage.php';
                            $imageModel = new ProductImage();
                            $count = count($_FILES['images']['name']);
                            for ($i = 0; $i < $count; $i++) {
                                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                                    $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                                    $fname = uniqid('prod_extra_', true) . '.' . $ext;
                                    move_uploaded_file($_FILES['images']['tmp_name'][$i], $uploadDir . $fname);
                                    $imageModel->create([
                                        'product_id' => $id,
                                        'filename' => $fname,
                                        'sort_order' => $i
                                    ]);
                                }
                            }
                        }
                        $message = '<span class="text-green-600">✓ Product updated successfully!</span>';
                    } catch (PDOException $e) {
                        $message = '<span class="text-red-600">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                    }
                }
            }
        } elseif ($action === 'delete' && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            try {
                $productModel->delete($id);
                $message = '<span class="text-green-600">✓ Product deleted successfully!</span>';
            } catch (PDOException $e) {
                $message = '<span class="text-red-600">Error deleting product: ' . htmlspecialchars($e->getMessage()) . '</span>';
            }
        }
        $products = $productModel->findAll();
        $categories = $categoryModel->findAll();
        $editProduct = null;
        if ($action === 'edit' && isset($_GET['id'])) {
            $editProduct = $productModel->findById((int)$_GET['id']);
        }
        $this->render('admin/products', [
            'products' => $products,
            'categories' => $categories,
            'editProduct' => $editProduct,
            'message' => $message
        ]);
    }

    public function bookings() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/BookingOrder.php';
        $bookingOrderModel = new BookingOrder();
    
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $orderId = (int)($_POST['order_id'] ?? 0);
    
            if ($action === 'verify' && $orderId) {
                $message = $bookingOrderModel->verifyPayment($orderId)
                    ? '✓ Booking verified successfully'
                    : '✗ Failed to verify booking';
            }
        }
    
        $paidBookings = $bookingOrderModel->findAllPaid();
        $verifiedBookings = $bookingOrderModel->findAllVerified();
        $bookings = array_merge($paidBookings, $verifiedBookings);
        usort($bookings, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
    
        $this->render('admin/orders', ['message' => $message, 'bookings' => $bookings]);

    }
    

    public function orders() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/Order.php';
        require_once __DIR__ . '/../Models/User.php';
        $orderModel = new Order();
        $userModel = new User();
        $orders = $orderModel->findAll(50);
        $this->render('admin/orders', ['orders' => $orders, 'userModel' => $userModel]);
    }
    
    public function testimonials() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/Testimonial.php';
        $testimonialModel = new Testimonial();
        $testimonials = $testimonialModel->findAll();
        $this->render('admin/testimonials', ['testimonials' => $testimonials]);
    }
    
    public function users() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User();
        $users = $userModel->findAll();
        $this->render('admin/users', ['users' => $users]);
    }

    public function viewUser(int $id) {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User();
        $user = $userModel->findById($id);
        if (!$user) { http_response_code(404); echo 'User not found'; return; }
        $this->render('admin/user-view', ['user' => $user]);
    }

    public function deleteUser() {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false]); return; }
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        if (!$id) { echo json_encode(['success'=>false,'message'=>'Invalid user ID']); return; }
        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User();
        try {
            $ok = $userModel->delete($id);
            echo json_encode(['success'=>(bool)$ok]);
        } catch (Exception $e) {
            echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function suspendUser() {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false]); return; }
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        $suspend = isset($input['suspend']) ? (bool)$input['suspend'] : false;
        if (!$id) { echo json_encode(['success'=>false,'message'=>'Invalid user ID']); return; }
        require_once __DIR__ . '/../Models/User.php';
        $userModel = new User();
        try {
            $ok = $userModel->suspend($id, $suspend);
            echo json_encode(['success'=>(bool)$ok]);
        } catch (Exception $e) {
            echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function managePackages() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/Package.php';
        $packageModel = new Package();
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';
            $id = isset($_POST['package_id']) ? (int)$_POST['package_id'] : null;

            // Handle background upload
            $backgroundFile = null;
            if (!empty($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION);
                $backgroundFile = uniqid('pkg_bg_', true) . '.' . $ext;
                move_uploaded_file($_FILES['background_image']['tmp_name'], UPLOAD_PATH . $backgroundFile);
            }

            // Build inclusions dynamically from posted keys
            $inclusions = [];
            if (!empty($_POST['inclusions']) && is_array($_POST['inclusions'])) {
                foreach ($_POST['inclusions'] as $key => $arr) {
                    $label = trim($_POST['inclusion_labels'][$key] ?? $key);
                    $items = array_values(array_filter(array_map('trim', $arr), fn($v) => $v !== ''));
                    $inclusions[$label] = $items;
                }
            }
            $data = [
                'package_name' => trim($_POST['package_name'] ?? ''),
                'hotel_name' => trim($_POST['hotel_name'] ?? ''),
                'hotel_address' => trim($_POST['hotel_address'] ?? ''),
                'hotel_description' => trim($_POST['hotel_description'] ?? ''),
                'number_of_guests' => (int)($_POST['number_of_guests'] ?? 0),
                'price' => (float)($_POST['price'] ?? 0),
                'freebies' => trim($_POST['freebies'] ?? ''),
                'inclusions' => $inclusions,
            ];
            if ($backgroundFile) { $data['background_image'] = $backgroundFile; }

            if ($action === 'delete' && $id) {
                $packageModel->deletePackage($id);
                $message = '✓ Package deleted';
            } elseif ($action === 'edit' && $id) {
                $packageModel->updatePackage($id, $data);
                $message = '✓ Package updated';
            } else {
                $packageModel->createPackage($data);
                $message = '✓ Package created';
            }
        }

        $packages = $packageModel->findAll();
        $this->render('admin/packages', ['message' => $message, 'packages' => $packages]);
    }

    public function approveTestimonial() {
        Auth::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid testimonial ID']);
            return;
        }
        
        require_once __DIR__ . '/../Models/Testimonial.php';
        $testimonialModel = new Testimonial();
        
        try {
            $testimonialModel->approve($id);
            echo json_encode(['success' => true, 'message' => 'Testimonial approved successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function rejectTestimonial() {
        Auth::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid testimonial ID']);
            return;
        }
        
        require_once __DIR__ . '/../Models/Testimonial.php';
        $testimonialModel = new Testimonial();
        
        try {
            $testimonialModel->reject($id);
            echo json_encode(['success' => true, 'message' => 'Testimonial rejected successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteTestimonial() {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid testimonial ID']);
            return;
        }
        require_once __DIR__ . '/../Models/Testimonial.php';
        $testimonialModel = new Testimonial();
        try {
            $ok = $testimonialModel->delete((int)$id);
            echo json_encode(['success' => (bool)$ok]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function viewTestimonial($id) {
        Auth::requireAdmin();
        
        require_once __DIR__ . '/../Models/Testimonial.php';
        $testimonialModel = new Testimonial();
        $testimonial = $testimonialModel->findById($id);
        
        if (!$testimonial) {
            http_response_code(404);
            echo 'Testimonial not found';
            return;
        }
        
        $this->render('admin/testimonial-view', ['testimonial' => $testimonial]);
    }
    
    public function manageBookings() {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/BookingOrder.php';
        $bookingOrderModel = new BookingOrder();
        
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $orderId = (int)($_POST['order_id'] ?? 0);
            
            if ($action === 'verify' && $orderId) {
                if ($bookingOrderModel->verifyPayment($orderId)) {
                    $message = '✓ Booking verified successfully';
                } else {
                    $message = '✗ Failed to verify booking';
                }
            }
        }
        
        $paidBookings = $bookingOrderModel->findAllPaid();
        $verifiedBookings = $bookingOrderModel->findAllVerified();
        $bookings = array_merge($paidBookings, $verifiedBookings);
        usort($bookings, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        $this->render('admin/orders', ['message' => $message, 'bookings' => $bookings]);

    }
    
    public function viewBooking($id) {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/BookingOrder.php';
        $bookingOrderModel = new BookingOrder();
        
        $booking = $bookingOrderModel->getOrderWithDetails($id);
        
        if (!$booking) {
            http_response_code(404);
            echo 'Booking not found';
            return;
        }
        
        $this->render('admin/booking-view', ['booking' => $booking]);
    }
    
    public function verifyBooking($id) {
        Auth::requireAdmin();
        require_once __DIR__ . '/../Models/BookingOrder.php';
        $bookingOrderModel = new BookingOrder();
        
        $bookingOrderModel->verifyPayment($id);
        
        header('Location: ' . BASE_URL . 'admin/orders');
        exit;
    }

    public function resolvePenalty() {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method not allowed';
            return;
        }
    
        $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
        if (!$orderId) {
            $_SESSION['message'] = 'Invalid order ID';
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }
    
        require_once __DIR__ . '/../Models/BookingOrder.php';
        $bookingOrderModel = new BookingOrder();
    
        try {
            $ok = $bookingOrderModel->resolvePenalty($orderId); 
            $_SESSION['message'] = $ok ? '✓ Penalty resolved successfully' : '✗ Failed to resolve penalty';
        } catch (Exception $e) {
            $_SESSION['message'] = 'Error: ' . $e->getMessage();
        }
    
        // Redirect back to bookings page
        header('Location: ' . BASE_URL . 'admin/orders');
        exit;
    }
    
    

    private function render($template, $data = []) {
        extract($data);
        $pageTitle = ucfirst(str_replace(['admin/', '/'], ['', ' - '], $template)) . ' | Joanne\'s';
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        include $viewsDir . '/layout.php';
    }
}
