<?php
// Admin Products Management - Enhanced Design with Fixed Structure
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
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
                        <h1 class="text-3xl font-serif-elegant font-bold text-gray-900">Product Management</h1>
                        <p class="text-gray-600 mt-1">Create and manage your product catalog</p>
                    </div>
                    <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                        <div class="text-xs text-gray-500">Total Products</div>
                        <div class="text-xl font-bold text-gray-900"><?php echo count($products); ?></div>
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

                <!-- Product Form -->
                <div class="form-card bg-white rounded-2xl shadow-xl p-8 mb-8 fade-in">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-<?php echo $editProduct ? 'edit' : 'plus'; ?> text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif-elegant font-bold text-gray-900">
                                <?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?>
                            </h2>
                            <p class="text-sm text-gray-600">Fill in the details below</p>
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" action="admin/products?action=<?php echo $editProduct ? 'edit&id=' . $editProduct['id'] : 'create'; ?>">
                        <?php if ($editProduct): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$editProduct['id']; ?>">
                            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editProduct['image'] ?? ''); ?>">
                        <?php endif; ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-tag text-yellow-600 mr-1"></i>
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($editProduct['name'] ?? ''); ?>" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" 
                                       placeholder="Enter product name" required>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-folder text-yellow-600 mr-1"></i>
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($editProduct['category_id']) && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-align-left text-yellow-600 mr-1"></i>
                                    Description
                                </label>
                                <textarea name="description" rows="4" 
                                          class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none"
                                          placeholder="Enter product description"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-dollar-sign text-yellow-600 mr-1"></i>
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-4 text-gray-500 font-bold">₱</span>
                                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($editProduct['price'] ?? ''); ?>" 
                                           class="input-enhanced w-full border-2 border-gray-200 rounded-lg pl-10 pr-4 py-3 focus:outline-none" 
                                           placeholder="0.00" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-boxes text-yellow-600 mr-1"></i>
                                    Stock Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($editProduct['stock_quantity'] ?? ''); ?>" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none" 
                                       placeholder="Enter stock quantity" min="0" required>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-image text-yellow-600 mr-1"></i>
                                    Main Image
                                </label>
                                <input type="file" name="image" accept="image/*" 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-2">Upload the primary product image</p>
                                <?php if (!empty($editProduct['image'])): ?>
                                    <div class="mt-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Current image:</p>
                                        <div class="image-preview inline-block">
                                            <img src="uploads/<?php echo htmlspecialchars($editProduct['image']); ?>" alt="Current Image" class="h-32 w-32 object-cover rounded-lg shadow-lg">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">
                                    <i class="fas fa-images text-yellow-600 mr-1"></i>
                                    Additional Images
                                </label>
                                <input type="file" name="images[]" accept="image/*" multiple 
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-2">Select multiple images for gallery</p>
                                <?php if (!empty($editProduct['id'])): ?>
                                    <?php 
                                        require_once dirname(__DIR__) . '/../Models/ProductImage.php';
                                        $imgModel = new ProductImage();
                                        $existingImages = $imgModel->findByProductId((int)$editProduct['id']);
                                    ?>
                                    <?php if (!empty($existingImages)): ?>
                                        <div class="mt-4">
                                            <p class="text-sm font-semibold text-gray-700 mb-3">Current gallery:</p>
                                            <div class="grid grid-cols-4 gap-3">
                                                <?php foreach ($existingImages as $img): ?>
                                                    <div class="image-preview">
                                                        <img src="uploads/<?php echo htmlspecialchars($img['filename']); ?>" class="w-full h-24 object-cover rounded-lg shadow-md" alt="">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="md:col-span-2">
                                <div class="flex items-center gap-8">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_featured" value="1" <?php echo (!empty($editProduct['is_featured'])) ? 'checked' : ''; ?>
                                               class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500 w-5 h-5">
                                        <span class="ml-3 text-gray-700 font-semibold">
                                            <i class="fas fa-star text-yellow-600 mr-1"></i>
                                            Featured Product
                                        </span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" <?php echo (!isset($editProduct['is_active']) || $editProduct['is_active']) ? 'checked' : ''; ?>
                                               class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                                        <span class="ml-3 text-gray-700 font-semibold">
                                            <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                            Active
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex gap-4">
                            <button type="submit" class="btn-gold-gradient px-8 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <?php echo $editProduct ? 'Update Product' : 'Create Product'; ?>
                                </span>
                            </button>
                            <?php if ($editProduct): ?>
                                <a href="admin/products" class="px-8 py-4 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Products Table -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 fade-in">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <h2 class="text-2xl font-serif-elegant font-bold text-gray-900">All Products</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your product inventory</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($products as $product): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="font-mono font-semibold text-gray-900">#<?php echo (int)$product['id']; ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <?php if (!empty($product['image'])): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="w-12 h-12 object-cover rounded-lg shadow" alt="">
                                                <?php else: ?>
                                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <?php 
                                                $cat = array_filter($categories, fn($c) => $c['id'] == $product['category_id']);
                                                echo $cat ? htmlspecialchars(array_values($cat)[0]['name']) : 'N/A';
                                            ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-gray-900">₱<?php echo number_format($product['price'], 2); ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge <?php echo $product['stock_quantity'] > 10 ? 'bg-green-100 text-green-700' : ($product['stock_quantity'] > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'); ?>">
                                                <i class="fas fa-box mr-1"></i>
                                                <?php echo (int)$product['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge <?php echo $product['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'; ?>">
                                                <i class="fas fa-<?php echo $product['is_active'] ? 'check-circle' : 'times-circle'; ?> mr-1"></i>
                                                <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="admin/products?action=edit&id=<?php echo (int)$product['id']; ?>" 
                                                   class="action-btn px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-semibold text-sm">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                                <button onclick="showDeleteModal(<?php echo (int)$product['id']; ?>)" 
                                                        class="action-btn px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold text-sm">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                                                <p class="text-gray-500 font-medium text-lg">No products found</p>
                                                <p class="text-gray-400 text-sm mt-1">Create your first product to get started</p>
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
        function showDeleteModal(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = 'admin/products?action=delete&id=' + productId;
            }
        }
    </script>
</body>
</html>