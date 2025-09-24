<?php
$pageTitle = "Products | Joanne's";
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <h1 class="text-2xl font-semibold">Browse Collection</h1>
        <form method="get" action="products" class="flex items-center gap-2">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>" placeholder="Search products..." class="border rounded px-3 py-2">
            <button class="px-4 py-2 bg-gold-400 text-white rounded hover:bg-gold-500">Search</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <aside class="md:col-span-1 bg-white rounded-lg shadow p-4 h-fit">
            <h2 class="font-semibold mb-3">Categories</h2>
            <ul class="space-y-2">
                <li>
                    <a href="products" class="<?php echo empty($currentCategory) ? 'text-gold-400' : 'text-gray-700'; ?> hover:text-gold-500">All</a>
                </li>
                <?php foreach (($categories ?? []) as $category): ?>
                    <li>
                        <a href="products?category=<?php echo urlencode($category['slug']); ?>" class="<?php echo (($currentCategory ?? '') === $category['slug']) ? 'text-gold-400' : 'text-gray-700'; ?> hover:text-gold-500">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <section class="md:col-span-3">
            <?php if (empty($products)): ?>
                <div class="bg-white p-8 rounded-lg shadow text-center text-gray-600">No products found.</div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-48 w-full object-cover">
                                <?php else: ?>
                                    <div class="text-5xl text-gold-400">👗</div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold mb-1 line-clamp-1"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="text-gold-400 font-bold mb-3">$<?php echo number_format($product['price'], 2); ?></div>
                                <div class="flex gap-2">
                                    <a href="products/show/<?php echo (int)$product['id']; ?>" class="px-3 py-2 border border-gold-400 text-gold-400 rounded hover:bg-gold-400 hover:text-white text-sm">View</a>
                                    <button onclick="addToCart(<?php echo (int)$product['id']; ?>)" class="px-3 py-2 bg-gold-400 text-white rounded hover:bg-gold-500 text-sm">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($totalPages) && $totalPages > 1): ?>
                    <div class="mt-8 flex items-center justify-center gap-2">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <a href="products?page=<?php echo $p; ?><?php echo $currentCategory ? '&category=' . urlencode($currentCategory) : ''; ?><?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>"
                               class="px-3 py-1 rounded border <?php echo (($currentPage ?? 1) == $p) ? 'bg-gold-400 text-white border-gold-400' : 'border-gray-300 text-gray-700 hover:bg-gray-50'; ?>">
                                <?php echo $p; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</div>


