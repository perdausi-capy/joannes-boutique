<?php
// (Remove all PHP logic at the top, only keep the HTML and use variables passed from the controller)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
            </div>
            <nav class="mt-4">
                <a href="/admin" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-dashboard mr-2"></i> Dashboard
                </a>
                <a href="/admin/products" class="block px-4 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="/admin/orders.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="/admin/users.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-users mr-2"></i> Users
                </a>
                <a href="/admin/testimonials.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-comments mr-2"></i> Testimonials
                </a>
                <a href="/admin/booking" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-2"></i> Booking
                </a>
                <a href="/" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-globe mr-2"></i> View Website
                </a>
                <a href="/auth/logout" class="block px-4 py-3 text-red-600 hover:bg-gray-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-800">Manage Products</h1>
            </header>
            <main class="p-6">
                <?php if ($message): ?>
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded"> <?php echo $message; ?> </div>
                <?php endif; ?>
                <!-- Product Form -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-4"><?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h2>
                    <form method="post" enctype="multipart/form-data" action="/admin/products?action=<?php echo $editProduct ? 'edit&id=' . $editProduct['id'] : 'create'; ?>">
                        <?php if ($editProduct): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$editProduct['id']; ?>">
                            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editProduct['image'] ?? ''); ?>">
                        <?php endif; ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 font-medium text-gray-700">Product/Package Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($editProduct['name'] ?? ''); ?>" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent" 
                                       placeholder="Enter product name" required>
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($editProduct['category_id']) && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-1 font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent"
                                          placeholder="Enter product description"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-gray-700">Price <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($editProduct['price'] ?? ''); ?>" 
                                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent" 
                                           placeholder="0.00" required>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-gray-700">Stocks <span class="text-red-500">*</span></label>
                                <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($editProduct['stock_quantity'] ?? ''); ?>" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent" 
                                       placeholder="Enter stock quantity" min="0" required>
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-gray-700">Main Image</label>
                                <input type="file" name="image" accept="image/*" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                                <p class="text-xs text-gray-500 mb-2">Upload the primary image for this product</p>
                                <?php if (!empty($editProduct['image'])): ?>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 mb-1">Current image:</p>
                                        <img src="/uploads/<?php echo htmlspecialchars($editProduct['image']); ?>" alt="Current Image" class="h-24 w-24 object-cover rounded-lg shadow">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-gray-700">Additional Images</label>
                                <input type="file" name="images[]" accept="image/*" multiple 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                                <p class="text-xs text-gray-500 mb-2">Select multiple images to show in the gallery</p>
                                <?php if (!empty($editProduct['id'])): ?>
                                    <?php 
                                        require_once dirname(__DIR__) . '/../Models/ProductImage.php';
                                        $imgModel = new ProductImage();
                                        $existingImages = $imgModel->findByProductId((int)$editProduct['id']);
                                    ?>
                                    <?php if (!empty($existingImages)): ?>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 mb-2">Current additional images:</p>
                                            <div class="grid grid-cols-4 gap-2">
                                                <?php foreach ($existingImages as $img): ?>
                                                    <div class="bg-gray-50 p-2 rounded-lg border">
                                                        <img src="/uploads/<?php echo htmlspecialchars($img['filename']); ?>" class="w-full h-20 object-cover rounded" alt="">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="md:col-span-2 flex items-center gap-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_featured" value="1" <?php echo (!empty($editProduct['is_featured'])) ? 'checked' : ''; ?>
                                           class="rounded border-gray-300 text-gold-400 focus:ring-gold-400">
                                    <span class="ml-2 text-gray-700">Featured Product</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" <?php echo (!isset($editProduct['is_active']) || $editProduct['is_active']) ? 'checked' : ''; ?>
                                           class="rounded border-gray-300 text-gold-400 focus:ring-gold-400">
                                    <span class="ml-2 text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-gold-400 to-yellow-400 text-white rounded shadow hover:from-gold-500 hover:to-yellow-500 transition-all duration-200 font-semibold tracking-wide"><?php echo $editProduct ? 'Update' : 'Create'; ?></button>
                            <?php if ($editProduct): ?>
                                <a href="/admin/products" class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <!-- Products Table -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">All Products</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="px-4 py-2 text-sm"> <?php echo (int)$product['id']; ?> </td>
                                    <td class="px-4 py-2 text-sm"> <?php echo htmlspecialchars($product['name']); ?> </td>
                                    <td class="px-4 py-2 text-sm"> <?php 
                                        $cat = array_filter($categories, fn($c) => $c['id'] == $product['category_id']);
                                        echo $cat ? htmlspecialchars(array_values($cat)[0]['name']) : 'N/A';
                                    ?> </td>
                                    <td class="px-4 py-2 text-sm"> $<?php echo number_format($product['price'], 2); ?> </td>
                                    <td class="px-4 py-2 text-sm"> <?php echo (int)$product['stock_quantity']; ?> </td>
                                    <td class="px-4 py-2 text-sm"> <?php echo $product['is_active'] ? 'Yes' : 'No'; ?> </td>
                                    <td class="px-4 py-2 text-sm">
                                        <a href="/admin/products?action=edit&id=<?php echo (int)$product['id']; ?>" class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 mr-2 transition">Edit</a>
                                        <button onclick="showDeleteModal(<?php echo (int)$product['id']; ?>)" class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <script>
function showDeleteModal(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = '/admin/products?action=delete&id=' + productId;
    }
}
</script>
</body>
</html>
