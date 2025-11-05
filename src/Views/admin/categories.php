<?php
// Admin Categories Management - Dashboard Style
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
        
        .stat-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }
        
        .gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        }
        
        .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: rgba(212, 175, 55, 0.05);
            transform: scale(1.01);
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
        
        .action-link {
            transition: all 0.2s ease;
        }
        
        .action-link:hover {
            transform: translateX(3px);
        }
        
        .input-enhanced {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }
        
        .input-enhanced:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            outline: none;
        }
        
        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
        }
        
        footer {
            display: none;
        }
        
        nav {
            display: block;
        }
        
        .modal-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }
        
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 ml-72 overflow-y-auto">
            <main class="p-8">
                <!-- Header -->
                <header class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-serif-elegant font-bold text-gray-900">Category Management</h1>
                            <p class="text-gray-600 mt-1">Organize and manage your product categories</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                                <div class="text-xs text-gray-500">Total Categories</div>
                                <div class="font-semibold text-gray-900"><?php echo count($categories); ?></div>
                            </div>
                            <button onclick="openAddModal()" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg text-sm">
                                <i class="fas fa-plus mr-1"></i> Add Category
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Alerts -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg fade-in">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="font-semibold"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg fade-in">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <span class="font-semibold"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Categories</p>
                                <p class="stat-number"><?php echo count($categories); ?></p>
                            </div>
                            <div class="icon-wrapper gradient-gold">
                                <i class="fas fa-folder text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">12%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.1s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Active Categories</p>
                                <p class="stat-number"><?php echo count(array_filter($categories, fn($c) => $c['is_active'])); ?></p>
                            </div>
                            <div class="icon-wrapper gradient-green">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">8%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.2s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Products</p>
                                <p class="stat-number"><?php echo array_sum(array_column($categories, 'product_count')); ?></p>
                            </div>
                            <div class="icon-wrapper gradient-blue">
                                <i class="fas fa-box text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">24%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.3s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Inactive Categories</p>
                                <p class="stat-number"><?php echo count(array_filter($categories, fn($c) => !$c['is_active'])); ?></p>
                            </div>
                            <div class="icon-wrapper gradient-purple">
                                <i class="fas fa-times-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-yellow-500 font-semibold">Needs attention</span>
                        </div>
                    </div>
                </div>

                <!-- Categories Table -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 fade-in" style="animation-delay: 0.4s;">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-serif-elegant font-bold text-gray-900">All Categories</h2>
                                <p class="text-sm text-gray-600 mt-1">Manage your category structure</p>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Slug</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Products</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($categories as $category): ?>
                                    <tr class="table-row">
                                        <td class="px-8 py-4">
                                            <span class="font-mono font-semibold text-sm text-gray-900">#<?php echo (int)$category['id']; ?></span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-tag text-white text-xs"></i>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <span class="font-mono text-sm text-gray-600"><?php echo htmlspecialchars($category['slug']); ?></span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <span class="status-badge bg-blue-100 text-blue-700">
                                                <i class="fas fa-box mr-1"></i>
                                                <?php echo (int)$category['product_count']; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <span class="status-badge <?php echo $category['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'; ?>">
                                                <i class="fas fa-<?php echo $category['is_active'] ? 'check-circle' : 'times-circle'; ?> mr-1"></i>
                                                <?php echo $category['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="flex items-center gap-2">
                                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($category), ENT_QUOTES); ?>)" 
                                                        class="action-link text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <button onclick="confirmDelete(<?php echo (int)$category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')" 
                                                        class="action-link text-red-600 hover:text-red-800 font-medium text-sm">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="6" class="px-8 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-folder-open text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-500 font-medium">No categories found</p>
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

    <!-- Add/Edit Modal -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay absolute inset-0" onclick="closeModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full relative z-10">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 id="modalTitle" class="text-xl font-serif-elegant font-bold text-gray-900">Add Category</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <form id="categoryForm" method="post" action="<?php echo rtrim(BASE_URL, '/'); ?>/admin/categories">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="categoryId">
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block mb-2 font-semibold text-gray-700 text-sm">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="categoryName" required
                                   class="input-enhanced w-full rounded-lg px-4 py-2 text-gray-900" 
                                   placeholder="Enter category name">
                        </div>
                        
                        <div>
                            <label class="block mb-2 font-semibold text-gray-700 text-sm">
                                Slug
                            </label>
                            <input type="text" name="slug" id="categorySlug"
                                   class="input-enhanced w-full rounded-lg px-4 py-2 text-gray-900" 
                                   placeholder="auto-generated">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from name</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="categoryActive" value="1" checked
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-4 h-4">
                                <span class="ml-2 text-gray-700 font-medium text-sm">Active Category</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-100 flex gap-3">
                        <button type="button" onclick="closeModal()" 
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all text-sm">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="btn-gold flex-1 px-4 py-2 text-white rounded-lg font-semibold shadow-lg text-sm">
                            <span id="submitText">Create</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-overlay absolute inset-0" onclick="closeDeleteModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-md w-full relative z-10">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Category?</h3>
                    <p class="text-gray-600 mb-6 text-sm">
                        Are you sure you want to delete "<span id="deleteCategoryName" class="font-semibold text-gray-900"></span>"? 
                        This action cannot be undone.
                    </p>
                    <div class="flex gap-3">
                        <button onclick="closeDeleteModal()" 
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all text-sm">
                            Cancel
                        </button>
                        <button onclick="deleteCategory()" 
                                class="flex-1 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-semibold hover:from-red-600 hover:to-red-700 shadow-lg transition-all text-sm">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteId = null;

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Category';
            document.getElementById('submitText').textContent = 'Create';
            document.getElementById('formAction').value = 'create';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryActive').checked = true;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function openEditModal(category) {
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('submitText').textContent = 'Update';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categorySlug').value = category.slug;
            document.getElementById('categoryActive').checked = category.is_active == 1;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function confirmDelete(id, name) {
            deleteId = id;
            document.getElementById('deleteCategoryName').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteId = null;
        }

        function deleteCategory() {
            if (deleteId) {
                window.location.href = '<?php echo rtrim(BASE_URL, '/'); ?>/admin/categories?action=delete&id=' + deleteId;
            }
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>