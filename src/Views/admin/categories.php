<?php
// Admin Categories Management - Enhanced Design
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
            background: linear-gradient(180deg, #d4af37, #f4d03f);
            border-radius: 4px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .font-serif-elegant {
            font-family: 'Cormorant Garamond', serif;
        }
        
        .stat-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        .icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .icon-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3));
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .stat-card:hover .icon-wrapper::before {
            opacity: 1;
        }
        
        .stat-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(-5deg);
        }
        
        .gradient-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 50%, #d4af37 100%);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 50%, #3b82f6 100%);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #34d399 50%, #10b981 100%);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }
        
        .gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 50%, #8b5cf6 100%);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
        }
        
        .table-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .table-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .table-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, #d4af37, #f4d03f);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .table-row:hover::before {
            transform: scaleY(1);
        }
        
        .table-row:hover {
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.08) 0%, transparent 100%);
            transform: translateX(4px);
        }
        
        .category-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .table-row:hover .category-icon {
            transform: rotate(10deg) scale(1.1);
        }
        
        .action-btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: currentColor;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .action-btn:hover::before {
            opacity: 0.1;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .modal-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-100px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .input-enhanced {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
            background: white;
        }
        
        .input-enhanced:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
            outline: none;
            transform: translateY(-1px);
        }
        
        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gold::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .btn-gold:hover::before {
            opacity: 1;
        }
        
        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.4);
        }
        
        .btn-gold span {
            position: relative;
            z-index: 1;
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 2px solid currentColor;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .search-box {
            transition: all 0.3s ease;
        }
        
        .search-box:focus-within {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .delete-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        footer{
            display:none;
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 ml-72">
            <main class="p-8">
                <!-- Header -->
                <header class="mb-8 fade-in">
                    <div class="header-card rounded-2xl shadow-xl p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-4xl font-serif-elegant font-bold text-gray-900 mb-2">
                                    Category Management
                                </h1>
                                <p class="text-gray-600 text-lg">Organize and manage your product categories efficiently</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl px-6 py-3 shadow-lg border border-gray-200">
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Categories</div>
                                    <div class="font-bold text-2xl text-gray-900 mt-1"><?php echo count($categories); ?></div>
                                </div>
                                <button onclick="openAddModal()" class="btn-gold px-6 py-3 text-white rounded-xl font-bold shadow-lg text-sm flex items-center gap-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Add Category</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Alerts -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 mb-6 p-5 text-white rounded-2xl flex items-center gap-4 shadow-xl fade-in">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <span class="font-semibold text-lg"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-gradient-to-r from-red-500 to-red-600 mb-6 p-5 text-white rounded-2xl flex items-center gap-4 shadow-xl fade-in">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-2xl"></i>
                        </div>
                        <span class="font-semibold text-lg"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="stat-card fade-in bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Total Categories</p>
                                <p class="text-4xl font-bold text-gray-900 mb-4"><?php echo count($categories); ?></p>
                                <div class="flex items-center text-sm">
                                    <div class="flex items-center px-2 py-1 bg-green-100 rounded-lg">
                                        <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                                        <span class="text-green-600 font-bold">12%</span>
                                    </div>
                                    <span class="text-gray-500 ml-2">from last month</span>
                                </div>
                            </div>
                            <div class="icon-wrapper gradient-gold transition-all duration-300">
                                <i class="fas fa-folder text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white rounded-2xl shadow-xl p-8 border border-gray-100" style="animation-delay: 0.1s;">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Total Products</p>
                                <p class="text-4xl font-bold text-gray-900 mb-4"><?php echo array_sum(array_column($categories, 'product_count')); ?></p>
                                <div class="flex items-center text-sm">
                                    <div class="flex items-center px-2 py-1 bg-green-100 rounded-lg">
                                        <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                                        <span class="text-green-600 font-bold">24%</span>
                                    </div>
                                    <span class="text-gray-500 ml-2">from last month</span>
                                </div>
                            </div>
                            <div class="icon-wrapper gradient-blue transition-all duration-300">
                                <i class="fas fa-box text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white rounded-2xl shadow-xl p-8 border border-gray-100" style="animation-delay: 0.2s;">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Avg Products</p>
                                <p class="text-4xl font-bold text-gray-900 mb-4"><?php echo count($categories) > 0 ? round(array_sum(array_column($categories, 'product_count')) / count($categories), 1) : 0; ?></p>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500">per category</span>
                                </div>
                            </div>
                            <div class="icon-wrapper gradient-purple transition-all duration-300">
                                <i class="fas fa-chart-line text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Table -->
                <div class="table-wrapper rounded-2xl shadow-xl fade-in" style="animation-delay: 0.3s;">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-serif-elegant font-bold text-gray-900">All Categories</h2>
                                <p class="text-sm text-gray-600 mt-1">Manage your category structure and organization</p>
                            </div>
                            <div class="search-box flex items-center gap-2 bg-gray-50 rounded-xl px-4 py-2 border border-gray-200">
                                <i class="fas fa-search text-gray-400"></i>
                                <input type="text" placeholder="Search categories..." 
                                       class="bg-transparent border-none outline-none text-sm text-gray-700 w-64"
                                       onkeyup="searchCategories(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full" id="categoriesTable">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Category</th>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Slug</th>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Products</th>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($categories as $category): ?>
                                    <tr class="table-row category-row" data-name="<?php echo strtolower(htmlspecialchars($category['name'])); ?>">
                                        <td class="px-8 py-5">
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg font-mono font-bold text-sm text-gray-700">
                                                <?php echo (int)$category['id']; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center">
                                                <div class="category-icon gradient-gold mr-4">
                                                    <i class="fas fa-tag text-white"></i>
                                                </div>
                                                <span class="font-semibold text-gray-900 text-base"><?php echo htmlspecialchars($category['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <code class="bg-gray-100 px-3 py-1 rounded-lg text-sm text-gray-700 font-mono"><?php echo htmlspecialchars($category['slug']); ?></code>
                                        </td>
                                        <td class="px-8 py-5">
                                            <span class="status-badge bg-blue-50 text-blue-700 border-blue-200">
                                                <i class="fas fa-box mr-2"></i>
                                                <span class="font-bold"><?php echo (int)$category['product_count']; ?></span>
                                                <span class="ml-1">items</span>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-2">
                                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($category), ENT_QUOTES); ?>)" 
                                                        class="action-btn px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg font-semibold text-sm flex items-center gap-2">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </button>
                                                <button onclick="confirmDelete(<?php echo (int)$category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')" 
                                                        class="action-btn px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-semibold text-sm flex items-center gap-2">
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="px-8 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                                    <i class="fas fa-folder-open text-gray-300 text-4xl"></i>
                                                </div>
                                                <p class="text-gray-700 font-semibold text-lg mb-2">No categories found</p>
                                                <p class="text-gray-500 text-sm mb-6">Create your first category to get started</p>
                                                <button onclick="openAddModal()" class="btn-gold px-6 py-3 text-white rounded-xl font-bold shadow-lg text-sm">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Add Your First Category
                                                </button>
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
            <div class="modal-content bg-white rounded-3xl shadow-2xl max-w-lg w-full relative z-10">
                <div class="p-8 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 id="modalTitle" class="text-2xl font-serif-elegant font-bold text-gray-900">Add Category</h3>
                            <p class="text-sm text-gray-600 mt-1">Fill in the details below</p>
                        </div>
                        <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <form id="categoryForm" method="post" action="<?php echo rtrim(BASE_URL, '/'); ?>/admin/categories">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="categoryId">
                    
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block mb-3 font-bold text-gray-700">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <input type="text" name="name" id="categoryName" required
                                       class="input-enhanced w-full rounded-xl pl-12 pr-4 py-3 text-gray-900 font-medium" 
                                       placeholder="Enter category name">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block mb-3 font-bold text-gray-700">
                                Slug
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-link"></i>
                                </div>
                                <input type="text" name="slug" id="categorySlug"
                                       class="input-enhanced w-full rounded-xl pl-12 pr-4 py-3 text-gray-900 font-medium" 
                                       placeholder="auto-generated">
                            </div>
                            <p class="text-xs text-gray-500 mt-2 ml-1">Leave empty to auto-generate from name</p>
                        </div>
                        
                        <input type="hidden" name="is_active" id="categoryActive" value="1">
                    </div>
                    
                    <div class="p-8 border-t border-gray-100 flex gap-4">
                        <button type="button" onclick="closeModal()" 
                                class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="btn-gold flex-1 px-6 py-3 text-white rounded-xl font-bold shadow-lg">
                            <span id="submitText">Create Category</span>
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
            <div class="modal-content bg-white rounded-3xl shadow-2xl max-w-md w-full relative z-10">
                <div class="p-8 text-center">
                    <div class="delete-icon-wrapper mx-auto mb-6">
                        <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Delete Category?</h3>
                    <p class="text-gray-600 mb-2">
                        Are you sure you want to delete
                    </p>
                    <p class="text-lg font-bold text-gray-900 mb-6">
                        "<span id="deleteCategoryName"></span>"?
                    </p>
                    <p class="text-sm text-gray-500 mb-8">This action cannot be undone.</p>
                    <div class="flex gap-4">
                        <button onclick="closeDeleteModal()" 
                                class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button onclick="deleteCategory()" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold hover:from-red-600 hover:to-red-700 shadow-lg transition-all">
                            <i class="fas fa-trash mr-2"></i>
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
            document.getElementById('submitText').textContent = 'Create Category';
            document.getElementById('formAction').value = 'create';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryActive').value = '1';
            document.getElementById('categoryModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('categoryName').focus();
            }, 100);
        }

        function openEditModal(category) {
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('submitText').textContent = 'Update Category';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categorySlug').value = category.slug;
            document.getElementById('categoryActive').value = category.is_active == 1 ? '1' : '0';
            document.getElementById('categoryModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('categoryName').focus();
            }, 100);
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

        function searchCategories(query) {
            const rows = document.querySelectorAll('.category-row');
            const searchTerm = query.toLowerCase().trim();
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                if (name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });

        // Auto-generate slug from name
        document.getElementById('categoryName').addEventListener('input', function(e) {
            if (document.getElementById('formAction').value === 'create') {
                const slug = e.target.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                document.getElementById('categorySlug').value = slug;
            }
        });

        // Add loading state to buttons
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            btn.disabled = true;
        });
    </script>
</body>
</html>