<?php
$pageTitle = ($product['name'] ?? "Product") . " | Joanne's";
?>
<div class="max-w-5xl mx-auto px-4 py-10">
    <?php if (empty($product)): ?>
        <div class="bg-white p-8 rounded-lg shadow text-center text-gray-600">Product not found.</div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <?php 
                    $images = [];
                    if (!empty($product['image'])) { $images[] = $product['image']; }
                    if (!empty($productImages)) {
                        foreach ($productImages as $pi) { $images[] = $pi['filename']; }
                    }
                ?>
                <?php if (!empty($images)): ?>
                    
                    <!-- Fallback for when Alpine.js doesn't load -->
                    <noscript>
                        <div class="h-[500px] bg-gray-100 flex items-center justify-center">
                            <img src="uploads/<?php echo htmlspecialchars($images[0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="max-h-[500px] w-full object-contain bg-white">
                        </div>
                    </noscript>
                    
                    <script>
                        window.productImages = <?php echo json_encode(array_values($images)); ?>;
                    </script>
                    <div x-data="{ idx: 0, imgs: window.productImages }" class="relative">
                        <div class="h-[500px] bg-gray-100 flex items-center justify-center relative">
                            <img :src="'/uploads/' + imgs[idx]" 
                                 :alt="product.name || 'Product Image'" 
                                 class="max-h-[500px] w-full object-contain bg-white transition-opacity duration-300"
                                 @load="$el.style.opacity = '1'"
                                 @error="$el.style.display='none'; $el.nextElementSibling.style.display='flex';"
                                 style="opacity: 0;">
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-100 text-7xl text-gold-400" style="display:none;">
                                <span>👗</span>
                                <p class="text-sm text-gray-600 ml-2">Image not available</p>
                            </div>
                            <!-- Loading spinner -->
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-100 text-gold-400" x-show="!imgs || imgs.length === 0">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gold-400"></div>
                            </div>
                        </div>
                        <button @click="idx = (idx - 1 + imgs.length) % imgs.length" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-10 h-10 flex items-center justify-center shadow"><i class="fas fa-chevron-left"></i></button>
                        <button @click="idx = (idx + 1) % imgs.length" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-10 h-10 flex items-center justify-center shadow"><i class="fas fa-chevron-right"></i></button>
                        <div class="flex gap-2 p-3 justify-center bg-gray-50 border-t">
                            <template x-for="(thumb, t) in imgs" :key="t">
                                <img :src="'/uploads/' + thumb" 
                                     @click="idx = t" 
                                     class="w-16 h-16 object-contain bg-white rounded border cursor-pointer transition-all duration-200" 
                                     :class="{ 'ring-2 ring-gold-400': idx === t }"
                                     @error="$el.style.display='none'"
                                     :alt="'Thumbnail ' + (t + 1)">
                            </template>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="h-96 flex items-center justify-center bg-gray-100 text-7xl text-gold-400">👗</div>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="text-3xl font-semibold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="text-2xl text-gold-400 font-bold mb-4">$<?php echo number_format($product['price'], 2); ?></div>
                <p class="text-gray-700 mb-6"><?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?></p>
                <div class="flex gap-3">
                    <button onclick="addToCart(<?php echo (int)$product['id']; ?>)" class="px-6 py-3 bg-gold-400 text-white rounded hover:bg-gold-500">Add to Cart</button>
                    <form method="post" action="/cart/buy-now">
                        <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                        <button class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if (!empty($relatedProducts)): ?>
            <div class="mt-12">
                <h2 class="text-xl font-semibold mb-4">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($relatedProducts as $rp): ?>
                        <a href="/products/show/<?php echo (int)$rp['id']; ?>" class="block bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <div class="bg-gray-100 h-48 flex items-center justify-center">
                                <?php if (!empty($rp['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($rp['image']); ?>" alt="<?php echo htmlspecialchars($rp['name']); ?>" class="h-48 w-full object-contain bg-white">
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


