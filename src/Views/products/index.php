<?php
$pageTitle = "Gallery | Joanne's";
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-light text-gray-800 mb-6">
            Our <span class="text-gold-400">Gallery</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
            Explore our stunning collection of elegant gowns and sophisticated suits. 
            Each piece is crafted with attention to detail and timeless style.
        </p>
        <form method="get" action="products" class="flex items-center justify-center gap-2 max-w-md mx-auto">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" placeholder="Search our gallery..." class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
            <button class="px-6 py-2 bg-gold-400 text-white rounded-lg hover:bg-gold-500 transition-colors">Search</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <aside class="md:col-span-1 bg-white rounded-lg shadow p-6 h-fit">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Filter by Category</h2>
            <ul class="space-y-3">
                <li>
                    <a href="products" class="block px-3 py-2 rounded-lg transition-colors <?php echo empty($currentCategory) ? 'bg-gold-100 text-gold-700 font-medium' : 'text-gray-700 hover:bg-gray-50'; ?>">
                        <i class="fas fa-th-large mr-2"></i>All Collections
                    </a>
                </li>
                <?php foreach (($categories ?? []) as $category): ?>
                    <li>
                        <a href="products?category=<?php echo urlencode($category['slug']); ?>" class="block px-3 py-2 rounded-lg transition-colors <?php echo (($currentCategory ?? '') === $category['slug']) ? 'bg-gold-100 text-gold-700 font-medium' : 'text-gray-700 hover:bg-gray-50'; ?>">
                            <i class="fas fa-tag mr-2"></i><?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <section class="md:col-span-3">
            <?php if (empty($products)): ?>
                <div class="bg-white p-12 rounded-lg shadow text-center">
                    <div class="text-6xl text-gold-400 mb-4">🖼️</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No items found</h3>
                    <p class="text-gray-600">Try adjusting your search or browse all collections.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($products as $product): ?>
                        <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                            <div class="relative bg-gray-100 h-64 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-64 w-full object-contain bg-white group-hover:scale-105 transition-transform duration-300">
                                <?php else: ?>
                                    <div class="text-6xl text-gold-400 opacity-50">👗</div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <a href="products/show/<?php echo (int)$product['id']; ?>" class="bg-white text-gray-800 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-1"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xl font-bold text-gold-400">$<?php echo number_format($product['price'], 2); ?></span>
                                    <?php if ($product['is_featured']): ?>
                                        <span class="bg-gold-100 text-gold-700 px-2 py-1 rounded-full text-xs font-medium">Featured</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex gap-2">
                                    <a href="products/show/<?php echo (int)$product['id']; ?>" class="flex-1 px-3 py-2 border border-gold-400 text-gold-400 rounded-lg hover:bg-gold-400 hover:text-white text-sm text-center transition-colors">View</a>
                                    <button onclick="addToCart(<?php echo (int)$product['id']; ?>, 1, true)" class="flex-1 px-3 py-2 bg-gold-400 text-white rounded-lg hover:bg-gold-500 text-sm transition-colors">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($totalPages) && $totalPages > 1): ?>
                    <div class="mt-12 flex items-center justify-center gap-2">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <a href="products?page=<?php echo $p; ?><?php echo $currentCategory ? '&category=' . urlencode($currentCategory) : ''; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>"
                               class="px-4 py-2 rounded-lg border transition-colors <?php echo (($currentPage ?? 1) == $p) ? 'bg-gold-400 text-white border-gold-400' : 'border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gold-400'; ?>">
                                <?php echo $p; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>


