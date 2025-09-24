<?php
$pageTitle = ($product['name'] ?? "Product") . " | Joanne's";
?>
<div class="max-w-5xl mx-auto px-4 py-10">
    <?php if (empty($product)): ?>
        <div class="bg-white p-8 rounded-lg shadow text-center text-gray-600">Product not found.</div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <?php if (!empty($product['image'])): ?>
                    <img src="/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full object-cover">
                <?php else: ?>
                    <div class="h-96 flex items-center justify-center bg-gray-100 text-7xl text-gold-400">👗</div>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="text-3xl font-semibold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="text-2xl text-gold-400 font-bold mb-4">$<?php echo number_format($product['price'], 2); ?></div>
                <p class="text-gray-700 mb-6"><?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?></p>
                <button onclick="addToCart(<?php echo (int)$product['id']; ?>)" class="px-6 py-3 bg-gold-400 text-white rounded hover:bg-gold-500">Add to Cart</button>
            </div>
        </div>

        <?php if (!empty($relatedProducts)): ?>
            <div class="mt-12">
                <h2 class="text-xl font-semibold mb-4">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($relatedProducts as $rp): ?>
                        <a href="/products/show/<?php echo (int)$rp['id']; ?>" class="block bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gray-100 h-40 flex items-center justify-center">
                                <?php if (!empty($rp['image'])): ?>
                                    <img src="/uploads/<?php echo htmlspecialchars($rp['image']); ?>" alt="<?php echo htmlspecialchars($rp['name']); ?>" class="h-40 w-full object-cover">
                                <?php else: ?>
                                    <div class="text-4xl text-gold-400">👗</div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <div class="font-medium line-clamp-1"><?php echo htmlspecialchars($rp['name']); ?></div>
                                <div class="text-gold-400 font-semibold">$<?php echo number_format($rp['price'], 2); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>


