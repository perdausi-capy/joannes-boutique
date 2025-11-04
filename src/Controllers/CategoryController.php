<?php
// src/Controllers/CategoryController.php

require_once __DIR__ . '/../Utils/Auth.php';
require_once __DIR__ . '/../Models/Category.php';

class CategoryController {
    private $categoryModel;
    private $baseUrl;

    public function __construct() {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Detect base URL dynamically
        $this->baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '/joannes-boutique/public';

        // Restrict access to admins only
        Auth::requireAdmin();

        $this->categoryModel = new Category();
    }

    /**
     * List all categories
     */
    public function index() {
        $categories = $this->categoryModel->findAll();

        // Add product count to each category
        foreach ($categories as &$category) {
            $category['product_count'] = $this->categoryModel->getProductCount($category['id']);
        }

        // Include the view
        include __DIR__ . '/../Views/admin/categories.php';
    }

    /**
     * Show create form
     */
    public function create() {
        $category = null;
        include __DIR__ . '/../Views/admin/category-form.php';
    }

    /**
     * Store new category
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validate name
        if (empty($name)) {
            $_SESSION['error'] = 'Category name is required';
            header('Location: ' . $this->baseUrl . '/admin/categories/create');
            exit;
        }

        // Auto-generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }

        // Check if slug already exists
        if ($this->categoryModel->findBySlug($slug)) {
            $_SESSION['error'] = 'Category with this name already exists';
            header('Location: ' . $this->baseUrl . '/admin/categories/create');
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if (!$imagePath) {
                $_SESSION['error'] = 'Failed to upload image. Please check file type and size.';
                header('Location: ' . $this->baseUrl . '/admin/categories/create');
                exit;
            }
        }

        // Prepare data
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath,
            'is_active' => $is_active
        ];

        // Create category
        if ($this->categoryModel->create($data)) {
            $_SESSION['success'] = 'Category created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create category';
        }

        header('Location: ' . $this->baseUrl . '/admin/categories');
        exit;
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        include __DIR__ . '/../Views/admin/category-form.php';
    }

    /**
     * Update category
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validate name
        if (empty($name)) {
            $_SESSION['error'] = 'Category name is required';
            header('Location: ' . $this->baseUrl . '/admin/categories/edit/' . $id);
            exit;
        }

        // Auto-generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }

        // Check if slug exists (excluding current category)
        $existingCategory = $this->categoryModel->findBySlug($slug);
        if ($existingCategory && $existingCategory['id'] != $id) {
            $_SESSION['error'] = 'Another category with this name already exists';
            header('Location: ' . $this->baseUrl . '/admin/categories/edit/' . $id);
            exit;
        }

        // Handle image upload
        $imagePath = $category['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImagePath = $this->handleImageUpload($_FILES['image']);
            if ($newImagePath) {
                // Delete old image
                if ($imagePath && file_exists(__DIR__ . '/../../public/uploads/' . $imagePath)) {
                    unlink(__DIR__ . '/../../public/uploads/' . $imagePath);
                }
                $imagePath = $newImagePath;
            }
        }

        // Prepare data
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $imagePath,
            'is_active' => $is_active
        ];

        // Update category
        if ($this->categoryModel->update($id, $data)) {
            $_SESSION['success'] = 'Category updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update category';
        }

        header('Location: ' . $this->baseUrl . '/admin/categories');
        exit;
    }

    /**
     * Delete category
     */
    public function delete($id) {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        // Check if category has products
        $productCount = $this->categoryModel->getProductCount($id);
        if ($productCount > 0) {
            $_SESSION['error'] = "Cannot delete category with {$productCount} product(s). Please reassign or delete products first.";
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        // Delete image file
        if ($category['image'] && file_exists(__DIR__ . '/../../public/uploads/' . $category['image'])) {
            unlink(__DIR__ . '/../../public/uploads/' . $category['image']);
        }

        // Delete category
        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = 'Category deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete category';
        }

        header('Location: ' . $this->baseUrl . '/admin/categories');
        exit;
    }

    /**
     * Toggle active status
     */
    public function toggle($id) {
        // Set JSON header
        header('Content-Type: application/json');

        $category = $this->categoryModel->find($id);

        if (!$category) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            exit;
        }

        $newStatus = $category['is_active'] ? 0 : 1;

        if ($this->categoryModel->update($id, ['is_active' => $newStatus])) {
            echo json_encode(['success' => true, 'status' => $newStatus]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        exit;
    }

    /**
     * Generate slug from string
     */
    private function generateSlug($string) {
        // Convert to lowercase
        $slug = strtolower(trim($string));
        
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        
        // Replace multiple hyphens with single hyphen
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Remove hyphens from start and end
        return trim($slug, '-');
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload($file) {
        // Allowed file types
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validate file type and size
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../../public/uploads/categories/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'cat_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'categories/' . $filename;
        }

        return false;
    }
}