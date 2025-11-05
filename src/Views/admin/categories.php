<?php
// Admin Categories Management - Refactored to match products.php structure
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            scrollbar-width: thin;
            scrollbar-color: #d4af37 transparent;
        }
        
        *::-webkit-scrollbar {
            width: 8px;
        }
        
        *::-webkit-scrollbar-track {
            background: transparent;
        }
        
        *::-webkit-scrollbar-thumb {
            background: #d4af37;
            border-radius: 4px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif-elegant {
            font-family: 'Cormorant Garamond', serif;
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #d4af37, #f4d03f);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            transform: scaleY(1);
        }
        
        .nav-link:hover {
            background: rgba(212, 175, 55, 0.1);
            padding-left: 1.5rem;
        }
        
        .nav-link.active {
            background: rgba(212, 175, 55, 0.15);
            color: #d4af37;
            font-weight: 600;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(249, 250, 251, 1) 100%);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .logo-text {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .form-card {
            transition: all 0.3s ease;
        }
        
        .form-card:hover {
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.1);
        }
        
        .input-enhanced {
            transition: all 0.3s ease;
        }
        
        .input-enhanced:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-gold-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gold-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
            transition: left 0.3s ease;
        }
        
        .btn-gold-gradient:hover::before {
            left: 0;
        }
        
        .btn-gold-gradient span {
            position: relative;
            z-index: 1;
        }
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: rgba(212, 175, 55, 0.05);
            transform: scale(1.005);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .action-btn {
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            animation: slideInDown 0.4s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .image-preview {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }
        
        footer {
            display: none;
        }
        
        nav {
            display: block;
        }
        
        nav::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="header-gradient shadow-sm border-b px-8 py-6 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-serif-elegant font-bold text-gray-900">Category Management</h1>
                        <p class="text-gray-600 mt-1">Organize your product categories</p>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                        <div class="text-xs text-gray-500">Total Categories</div>
                        <div class="text-xl font-bold text-gray-900"><?php echo count($categories); ?></div>
                    </div>
                </div>
            </header>

            <main class="p-8">
                <?php if ($message): ?>
                    <div class="alert-success mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg fade-in">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <span class="font-semibold"><?php echo $message; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-success mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg fade-in">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <span class="font-semibold"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg fade-in">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                        <span class="font-semibold"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Category Form -->
                <div class="form-card bg-white rounded-2xl shadow-xl p-8 mb-8 fade-in">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-<?php echo $editCategory ? 'edit' : 'plus'; ?> text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif-elegant font-bold text-gray-900">
                                <?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?>
                            </h2>
                            <p class="text-sm text-gray-600">Fill in the details below</p>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" action="<?php echo $this->baseUrl; ?>/admin/categories?action=<?php echo $editCategory ? 'edit' : 'create'; ?>">
                        <?php if ($editCategory): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$editCategory['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-tag text-yellow-600 mr-1"></i>
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($editCategory['name'] ?? ''); ?>" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" 
                                       placeholder="Enter category name" required>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-link text-yellow-600 mr-1"></i>
                                    Slug
                                </label>
                                <input type="text" name="slug" value="<?php echo htmlspecialchars($editCategory['slug'] ?? ''); ?>" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" 
                                       placeholder="auto-generated if empty">
                                <p class="text-xs text-gray-500 mt-2">Leave empty to auto-generate from name</p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-align-left text-yellow-600 mr-1"></i>
                                    Description
                                </label>
                                <textarea name="description" rows="4" 
                                          class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none"
                                          placeholder="Enter category description"><?php echo htmlspecialchars($editCategory['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-image text-yellow-600 mr-1"></i>
                                    Category Image
                                </label>
                                <input type="file" name="image" accept="image/*" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-2">Upload category image (JPG, PNG, GIF, WEBP - Max 5MB)</p>
                                <?php if (!empty($editCategory['image'])): ?>
                                    <div class="mt-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Current image:</p>
                                        <div class="image-preview inline-block">
                                            <img src="uploads/<?php echo htmlspecialchars($editCategory['image']); ?>" alt="Current Image" class="h-32 w-32 object-cover rounded-lg shadow-lg">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" <?php echo (!isset($editCategory['is_active']) || $editCategory['is_active']) ? 'checked' : ''; ?>
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                                    <span class="ml-3 text-gray-700 font-semibold">
                                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                        Active
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex gap-4">
                            <button type="submit" class="btn-gold-gradient px-8 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <?php echo $editCategory ? 'Update Category' : 'Create Category'; ?>
                                </span>
                            </button>
                            <?php if ($editCategory): ?>
                                <a href="<?php echo $this->baseUrl; ?>/admin/categories" class="px-8 py-4 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Categories Table -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 fade-in">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <h2 class="text-2xl font-serif-elegant font-bold text-gray-900">All Categories</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your category structure</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Slug</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Products</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($categories as $category): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="font-mono font-semibold text-gray-900">#<?php echo (int)$category['id']; ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if (!empty($category['image'])): ?>
                                                <img src="uploads/<?php echo htmlspecialchars($category['image']); ?>" class="w-12 h-12 object-cover rounded-lg shadow" alt="">
                                            <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm text-gray-600"><?php echo htmlspecialchars($category['slug']); ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge bg-blue-100 text-blue-700">
                                                <i class="fas fa-box mr-1"></i>
                                                <?php echo (int)$category['product_count']; ?> products
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button onclick="toggleStatus(<?php echo (int)$category['id']; ?>)" 
                                                    class="status-badge <?php echo $category['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'; ?> cursor-pointer">
                                                <i class="fas fa-<?php echo $category['is_active'] ? 'check-circle' : 'times-circle'; ?> mr-1"></i>
                                                <?php echo $category['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="<?php echo $this->baseUrl; ?>/admin/categories?action=edit&id=<?php echo (int)$category['id']; ?>" 
                                                   class="action-btn px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-semibold text-sm">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                                <button onclick="showDeleteModal(<?php echo (int)$category['id']; ?>)" 
                                                        class="action-btn px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-sm">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                                                <p class="text-gray-500 font-medium text-lg">No categories found</p>
                                                <p class="text-gray-400 text-sm mt-1">Create your first category to get started</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        function showDeleteModal(categoryId) {
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                window.location.href = '<?php echo $this->baseUrl; ?>/admin/categories?action=delete&id=' + categoryId;
            }
        }

        function toggleStatus(categoryId) {
            if (confirm('Are you sure you want to toggle this category status?')) {
                fetch('<?php echo $this->baseUrl; ?>/admin/categories/toggle/' + categoryId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
    </script>
</body>
</html>