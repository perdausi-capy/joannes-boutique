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
     * Main categories management page with form and list
     */
    public function index() {
        $message = '';
        $editCategory = null;

        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_GET['action'] ?? '';
            
            if ($action === 'create') {
                $message = $this->handleCreate();
            } elseif ($action === 'edit') {
                $message = $this->handleUpdate();
            }
        }

        // Check if editing
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $editCategory = $this->categoryModel->find((int)$_GET['id']);
            if (!$editCategory) {
                $_SESSION['error'] = 'Category not found';
                header('Location: ' . $this->baseUrl . '/admin/categories');
                exit;
            }
        }

        // Handle delete action
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $message = $this->handleDelete((int)$_GET['id']);
            header('Location: ' . $this->baseUrl . '/admin/categories');
            exit;
        }

        // Get all categories with product counts
        $categories = $this->categoryModel->findAll();
        foreach ($categories as &$category) {
            $category['product_count'] = $this->categoryModel->getProductCount($category['id']);
        }

        // Include the view from correct location
        require_once __DIR__ . '/../Views/admin/categories.php';
    }

    /**
     * Handle category creation
     */
    private function handleCreate() {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validate name
        if (empty($name)) {
            return 'Category name is required';
        }

        // Auto-generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }

        // Check if slug already exists
        if ($this->categoryModel->findBySlug($slug)) {
            return 'Category with this name already exists';
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if (!$imagePath) {
                return 'Failed to upload image. Please check file type and size.';
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
            return 'Category created successfully';
        } else {
            return 'Failed to create category';
        }
    }

    /**
     * Handle category update
     */
    private function handleUpdate() {
        $id = (int)($_POST['id'] ?? 0);
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return 'Category not found';
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        // Validate name
        if (empty($name)) {
            return 'Category name is required';
        }

        // Auto-generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }

        // Check if slug exists (excluding current category)
        $existingCategory = $this->categoryModel->findBySlug($slug);
        if ($existingCategory && $existingCategory['id'] != $id) {
            return 'Another category with this name already exists';
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
            return 'Category updated successfully';
        } else {
            return 'Failed to update category';
        }
    }

    /**
     * Handle category deletion
     */
    private function handleDelete($id) {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            $_SESSION['error'] = 'Category not found';
            return '';
        }

        // Check if category has products
        $productCount = $this->categoryModel->getProductCount($id);
        if ($productCount > 0) {
            $_SESSION['error'] = "Cannot delete category with {$productCount} product(s). Please reassign or delete products first.";
            return '';
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
        
        return '';
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