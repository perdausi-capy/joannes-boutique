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
        require_once __DIR__ . '/../Models/Booking.php';
        $bookingModel = new Booking();
        $bookings = $bookingModel->findRecent(50);
        $this->render('admin/bookings', ['bookings' => $bookings]);
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
