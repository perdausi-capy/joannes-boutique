<?php $pageTitle = "Packages | Joanne's"; ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-light text-gray-800 mb-6">
            Our <span class="text-gold-400">Packages</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Discover our carefully curated collections designed for every special occasion. 
            From elegant gowns to sophisticated suits, we offer complete packages tailored to your needs.
        </p>
    </div>

    <?php if (empty($packages)): ?>
        <div class="text-center py-16">
            <div class="text-6xl text-gold-400 mb-4">📦</div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">No Packages Available</h2>
            <p class="text-gray-600">We're currently updating our package offerings. Please check back soon!</p>
        </div>
    <?php else: ?>
        <?php foreach ($packages as $package): ?>
            <section class="mb-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-light text-gray-800 mb-4">
                        <?php echo htmlspecialchars($package['category']['name']); ?>
                    </h2>
                    <?php if (!empty($package['category']['description'])): ?>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            <?php echo htmlspecialchars($package['category']['description']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($package['products'] as $product): ?>
                        <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                            <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <?php if (!empty($product['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="w-full h-64 object-contain bg-white">
                                <?php else: ?>
                                    <div class="text-6xl text-gold-400 opacity-50">👗</div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-gold-400">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        Stock: <?php echo (int)$product['stock_quantity']; ?>
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="/products/show/<?php echo $product['id']; ?>" 
                                       class="flex-1 px-4 py-2 border border-gold-400 text-gold-400 rounded-lg hover:bg-gold-400 hover:text-white transition-colors text-center">
                                        View Details
                                    </a>
                                    <button onclick="addToCart(<?php echo $product['id']; ?>, 1, true)" 
                                            class="flex-1 px-4 py-2 bg-gold-400 text-white rounded-lg hover:bg-gold-500 transition-colors">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center mt-8">
                    <a href="/products?category=<?php echo urlencode($package['category']['slug']); ?>" 
                       class="inline-block px-8 py-3 border-2 border-gold-400 text-gold-400 rounded-lg font-semibold hover:bg-gold-400 hover:text-white transition-colors">
                        View All <?php echo htmlspecialchars($package['category']['name']); ?>
                    </a>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Call to Action -->
    <section class="mt-20 bg-gradient-to-r from-gold-400 to-yellow-400 rounded-2xl p-12 text-center">
        <h2 class="text-3xl md:text-4xl font-light text-white mb-4">
            Need a Custom Package?
        </h2>
        <p class="text-xl text-gold-100 mb-8 max-w-2xl mx-auto">
            Let us create a personalized package tailored to your specific needs and preferences.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/booking" 
               class="inline-block px-8 py-4 bg-white text-gold-400 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Book Consultation
            </a>
            <a href="/contact" 
               class="inline-block px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-gold-400 transition-colors">
                Contact Us
            </a>
        </div>
    </section>
</div>
