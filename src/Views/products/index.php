<?php
$pageTitle = "Gallery | Joanne's";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap');
    
    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }
    
    .gallery-hero {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(184, 148, 31, 0.12) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .gallery-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(5deg); }
    }
    
    .category-sidebar {
        transition: all 0.3s ease;
    }
    
    .category-link {
        position: relative;
        overflow: hidden;
    }
    
    .category-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: linear-gradient(to bottom, #d4af37, #f4d03f);
        transition: height 0.3s ease;
    }
    
    .category-link:hover::before,
    .category-link.active::before {
        height: 100%;
    }
    
    .product-card-gallery {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .product-card-gallery::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .product-card-gallery:hover::after {
        opacity: 1;
    }
    
    .product-card-gallery:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 60px rgba(212, 175, 55, 0.25);
    }
    
    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
    }
    
    .product-image-wrapper img {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .product-card-gallery:hover .product-image-wrapper img {
        transform: scale(1.15);
    }
    
    .product-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        padding: 1.5rem;
    }
    
    .product-card-gallery:hover .product-overlay {
        opacity: 1;
    }
    
    .featured-badge {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF8C00 100%);
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.5);
        box-shadow: 
            0 10px 25px rgba(255, 165, 0, 0.5),
            0 5px 15px rgba(255, 215, 0, 0.4),
            inset 0 2px 4px rgba(255, 255, 255, 0.6),
            inset 0 -2px 4px rgba(0, 0, 0, 0.1);
        animation: golden-pulse 3s ease-in-out infinite;
        backdrop-filter: blur(4px);
    }

    .featured-badge::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to right,
            transparent,
            rgba(255, 255, 255, 0.4),
            transparent
        );
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }

    .featured-badge::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            180deg,
            rgba(255, 255, 255, 0.3) 0%,
            transparent 50%,
            rgba(0, 0, 0, 0.1) 100%
        );
        border-radius: inherit;
        pointer-events: none;
    }
    
    @keyframes golden-pulse {
        0%, 100% { 
            box-shadow: 
                0 10px 25px rgba(255, 165, 0, 0.5),
                0 5px 15px rgba(255, 215, 0, 0.4),
                inset 0 2px 4px rgba(255, 255, 255, 0.6),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
        50% { 
            box-shadow: 
                0 15px 35px rgba(255, 165, 0, 0.6),
                0 8px 20px rgba(255, 215, 0, 0.5),
                inset 0 2px 4px rgba(255, 255, 255, 0.7),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
    }

    @keyframes shine {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(200%) rotate(45deg); }
    }

    .reserved-badge {
        background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow: 
            0 8px 20px rgba(220, 38, 38, 0.4),
            0 4px 12px rgba(153, 27, 27, 0.3),
            inset 0 2px 4px rgba(255, 255, 255, 0.3),
            inset 0 -2px 4px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(4px);
    }

    .reserved-badge::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            180deg,
            rgba(255, 255, 255, 0.2) 0%,
            transparent 50%,
            rgba(0, 0, 0, 0.15) 100%
        );
        border-radius: inherit;
        pointer-events: none;
    }

    .badge-icon {
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        animation: icon-glow 2s ease-in-out infinite;
    }

    @keyframes icon-glow {
        0%, 100% { filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3)); }
        50% { filter: drop-shadow(0 2px 4px rgba(255, 255, 255, 0.5)); }
    }

    .badge-text {
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .search-bar-enhanced {
        position: relative;
        display: none;
    }
    
    .search-bar-enhanced::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, #d4af37, #f4d03f, #d4af37);
        border-radius: 0.75rem;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }
    
    .search-bar-enhanced:focus-within::before {
        opacity: 1;
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
    
    .pagination-btn {
        transition: all 0.3s ease;
    }
    
    .pagination-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }
    
    .empty-state {
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
    
    .scroll-fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .scroll-fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Loading States */
    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #d4af37;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .availability-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .availability-badge.available {
        background-color: #d1fae5;
        color: #065f46;
    }

    .availability-badge.unavailable {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .availability-badge.checking {
        background-color: #fef3c7;
        color: #92400e;
    }

    .price-breakdown {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-top: 1rem;
    }

    .error-message {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        display: none;
    }

    .error-message.show {
        display: block;
        animation: shake 0.5s;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .success-message {
        background-color: #d1fae5;
        color: #065f46;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        display: none;
    }

    .success-message.show {
        display: block;
        animation: fadeInUp 0.5s;
    }
</style>

<div class="gallery-hero py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12">
            <span class="text-yellow-600 font-semibold text-sm tracking-wider uppercase mb-4 inline-block">Collections</span>
            <h1 class="font-serif-elegant text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                Explore Our <span class="text-yellow-600">Gallery</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                Discover stunning collections of elegant gowns and sophisticated suits. 
                Each piece is meticulously crafted with attention to detail and timeless style.
            </p>
            
            <div class="search-bar-enhanced max-w-2xl mx-auto">
                <div class="flex items-center gap-3 bg-white rounded-xl shadow-lg p-2 border-2 border-transparent transition-all">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="searchInput"
                               value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>"
                               placeholder="Search gowns, suits, accessories..." 
                               class="w-full pl-12 pr-4 py-3 border-0 rounded-lg focus:outline-none focus:ring-0 text-gray-700">
                    </div>
                    <button type="button" onclick="performSearch()" class="btn-gold-gradient px-8 py-3 text-white rounded-lg font-semibold hover:shadow-xl whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            Search
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <aside class="lg:col-span-1">
            <div class="category-sidebar bg-white rounded-2xl shadow-xl p-6 sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-filter text-white"></i>
                    </div>
                    <h2 class="text-xl font-serif-elegant font-bold text-gray-900">Categories</h2>
                </div>
                
                <ul class="space-y-2" id="categoryList">
                    <li>
                        <a href="#" onclick="filterByCategory(''); return false;" 
                           class="category-link active flex items-center gap-3 px-4 py-3 rounded-lg transition-all bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-700 font-semibold" 
                           data-category="">
                            <i class="fas fa-th-large w-5"></i>
                            <span>All Collections</span>
                        </a>
                    </li>
                    <?php foreach (($categories ?? []) as $category): ?>
                        <li>
                            <a href="#" 
                               onclick="filterByCategory('<?php echo htmlspecialchars($category['slug'], ENT_QUOTES); ?>'); return false;" 
                               class="category-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-gray-700 hover:bg-gray-50" 
                               data-category="<?php echo htmlspecialchars($category['slug'], ENT_QUOTES); ?>">
                                <i class="fas fa-tag w-5"></i>
                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="mt-8 p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                    <div class="text-center">
                        <i class="fas fa-crown text-yellow-600 text-3xl mb-3"></i>
                        <h3 class="font-serif-elegant font-bold text-gray-900 mb-2">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-3">Our experts are ready to assist you</p>
                        <a href="contact" class="inline-block px-4 py-2 bg-yellow-600 text-white rounded-lg text-sm font-semibold hover:bg-yellow-700 transition-colors">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Products Grid -->
        <section class="lg:col-span-3">
            <div id="loadingState" class="hidden text-center py-20">
                <div class="loading-spinner mx-auto mb-4"></div>
                <p class="text-gray-600">Loading products...</p>
            </div>

            <div id="productsContainer">
                <?php if (empty($products)): ?>
                    <div class="empty-state bg-white p-16 rounded-2xl shadow-xl text-center">
                        <div class="inline-block p-6 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-full mb-6">
                            <i class="fas fa-search text-yellow-600 text-6xl"></i>
                        </div>
                        <h3 class="font-serif-elegant text-3xl font-bold text-gray-900 mb-3">No Items Found</h3>
                        <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">We couldn't find any items matching your search. Try adjusting your filters or browse all collections.</p>
                        <a href="#" onclick="filterByCategory(''); return false;" class="inline-block px-8 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg">
                            View All Collections
                        </a>
                    </div>
                <?php else: ?>
                    <div class="mb-6 flex items-center justify-between">
                        <p class="text-gray-600">
                            <span class="font-semibold text-gray-900" id="productCount"><?php echo count($products); ?></span> items found
                        </p>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-sort"></i>
                            <span>Sorted by: <strong>Featured</strong></span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8" id="productGrid">
                        <?php foreach ($products as $index => $product): ?>
                            <div class="product-card-gallery scroll-fade-in bg-white rounded-2xl overflow-hidden shadow-lg" style="transition-delay: <?php echo $index * 50; ?>ms;" data-product-id="<?php echo (int)$product['id']; ?>">
                                <div class="product-image-wrapper relative h-80">
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                                            <i class="fas fa-crown text-yellow-400 text-7xl opacity-20"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Badges Container -->
                                    <div class="absolute top-2 right-2 flex flex-col gap-2">
                                        <?php if ($product['is_reserved'] ?? false): ?>
                                            <div class="reserved-badge px-2 py-1 rounded-full text-white text-xs font-semibold shadow-lg" style="background-color: #EF4444;">
                                                Reserved
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($product['is_featured'] ?? false): ?>
                                            <div class="featured-badge px-3 py-1 rounded-full text-white text-xs font-bold shadow-lg">
                                                ⭐ Featured
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="product-overlay">
                                        <a href="products/show/<?php echo (int)$product['id']; ?>" 
                                           class="flex items-center gap-2 bg-white text-gray-900 px-5 py-2 rounded-lg font-semibold hover:bg-yellow-400 hover:text-white transition-all shadow-lg transform hover:scale-105">
                                            <i class="fas fa-eye"></i>
                                            Quick View
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <h3 class="font-serif-elegant text-xl font-bold text-gray-900 mb-2 line-clamp-1">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                        <?php echo htmlspecialchars($product['description'] ?? ''); ?>
                                    </p>
                                    
                                    <div class="flex items-center justify-between mb-5">
                                        <div>
                                            <span class="text-xs text-gray-500 block">Starting at</span>
                                            <span class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-700 bg-clip-text text-transparent">
                                                ₱<?php echo number_format($product['price'], 2); ?>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1 text-yellow-500">
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                            <i class="fas fa-star text-sm"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <!-- <a href="products/show/<?php echo (int)$product['id']; ?>" 
                                           class="flex-1 text-center px-4 py-3 border-2 border-yellow-600 text-yellow-600 rounded-lg hover:bg-yellow-600 hover:text-white transition-all font-semibold">
                                            Details
                                        </a> -->
                                        <a href="products/show/<?php echo (int)$product['id']; ?>" 
                                           class="flex-1 text-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all font-semibold shadow-lg">
                                            Rent Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination will be dynamically generated -->
                    <div id="paginationContainer" class="mt-16"></div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Rental Modal -->
<div id="rentalModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Rent This Item</h3>
                    <button onclick="closeRentalModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="rentalForm" method="post" action="rental/product">
                    <input type="hidden" name="product_id" id="rentalProductId">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="font-medium" id="rentalItemName"></div>
                            <div class="text-sm text-gray-600">₱<span id="rentalItemPrice"></span>/day</div>
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div id="availabilityStatus" class="mb-4 hidden">
                        <div class="availability-badge checking">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Checking availability...</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rental Start Date</label>
                        <input type="date" name="rental_start" id="rentalStartDate" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rental End Date</label>
                        <input type="date" name="rental_end" id="rentalEndDate" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Price Breakdown -->
                    <div id="priceBreakdown" class="price-breakdown hidden">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Daily Rate:</span>
                            <span class="text-sm font-semibold">₱<span id="dailyRate">0.00</span></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Number of Days:</span>
                            <span class="text-sm font-semibold"><span id="numberOfDays">0</span> days</span>
                        </div>
                        <div class="border-t border-gray-300 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900">Total:</span>
                                <span class="font-bold text-xl text-yellow-600">₱<span id="totalPrice">0.00</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="availabilityError" class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="errorText"></span>
                    </div>

                    <!-- Success Message -->
                    <div id="availabilitySuccess" class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <span>Item is available for selected dates!</span>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Size (Optional)</label>
                        <input type="text" name="size" id="rentalSize" placeholder="e.g., Small, Medium, Large" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name</label>
                        <input type="text" name="contact_name" id="contactName" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" id="contactEmail" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="tel" name="contact_phone" id="contactPhone" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeRentalModal()" 
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="submitRentalBtn"
                                class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Global state
let currentCategory = '';
let currentSearch = '';
let currentPage = 1;
let isAvailable = false;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimation();
    initSearchListener();
    initDateListeners();
    setMinDates();
    
    // Load initial search term if exists
    const searchInput = document.getElementById('searchInput');
    if (searchInput && searchInput.value) {
        currentSearch = searchInput.value;
    }
});

// Scroll reveal animation
function initScrollAnimation() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.scroll-fade-in').forEach(el => {
        observer.observe(el);
    });
}

// Search with debounce
function initSearchListener() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 500);
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch();
        }
    });
}

// Perform search
async function performSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) {
        console.error('Search input not found');
        return;
    }
    
    const searchTerm = searchInput.value.trim();
    currentSearch = searchTerm;
    currentPage = 1;
    
    console.log('Performing search:', searchTerm);
    await loadProducts();
}

// Filter by category
async function filterByCategory(category) {
    currentCategory = category;
    currentPage = 1;
    
    // Update active state
    document.querySelectorAll('.category-link').forEach(link => {
        link.classList.remove('active', 'bg-gradient-to-r', 'from-yellow-50', 'to-yellow-100', 'text-yellow-700', 'font-semibold');
        link.classList.add('text-gray-700', 'hover:bg-gray-50');
    });
    
    const activeLink = document.querySelector(`.category-link[data-category="${category}"]`);
    if (activeLink) {
        activeLink.classList.add('active', 'bg-gradient-to-r', 'from-yellow-50', 'to-yellow-100', 'text-yellow-700', 'font-semibold');
        activeLink.classList.remove('text-gray-700', 'hover:bg-gray-50');
    }
    
    await loadProducts();
}

// Load products via AJAX (Fallback to page reload if API not available)
async function loadProducts() {
    const loadingState = document.getElementById('loadingState');
    const productsContainer = document.getElementById('productsContainer');
    
    if (!loadingState || !productsContainer) {
        console.error('Required DOM elements not found');
        return;
    }
    
    // Show loading
    loadingState.classList.remove('hidden');
    productsContainer.classList.add('hidden');
    
    try {
        const params = new URLSearchParams({
            page: currentPage
        });
        
        if (currentCategory) params.append('category', currentCategory);
        if (currentSearch) params.append('search', currentSearch);
        
        // Add ajax parameter to use existing controller
        params.append('ajax', '1');
        
        console.log('Fetching products with params:', params.toString());
        
        const response = await fetch(`products?${params.toString()}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response as text first to check what we're receiving
        const responseText = await response.text();
        console.log('Raw response (first 500 chars):', responseText.substring(0, 500));
        
        // Try to parse as JSON
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON Parse Error:', parseError);
            console.error('Full response:', responseText);
            throw new Error('Server returned invalid JSON. Check console for details.');
        }
        
        console.log('Parsed data:', data);
        
        if (data.success) {
            renderProducts(data.products, data.pagination);
        } else {
            showError(data.message || 'Failed to load products');
        }
    } catch (error) {
        console.error('Error loading products:', error);
        showError('An error occurred while loading products: ' + error.message);
    } finally {
        loadingState.classList.add('hidden');
        productsContainer.classList.remove('hidden');
    }
}

// Render products to DOM
function renderProducts(products, pagination) {
    const productGrid = document.getElementById('productGrid');
    const productCount = document.getElementById('productCount');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (!products || products.length === 0) {
        productGrid.innerHTML = `
            <div class="col-span-full empty-state bg-white p-16 rounded-2xl shadow-xl text-center">
                <div class="inline-block p-6 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-full mb-6">
                    <i class="fas fa-search text-yellow-600 text-6xl"></i>
                </div>
                <h3 class="font-serif-elegant text-3xl font-bold text-gray-900 mb-3">No Items Found</h3>
                <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">We couldn't find any items matching your search.</p>
                <a href="#" onclick="filterByCategory(''); return false;" class="inline-block px-8 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg">
                    View All Collections
                </a>
            </div>
        `;
        productCount.textContent = '0';
        paginationContainer.innerHTML = '';
        return;
    }
    
    // Update count
    productCount.textContent = pagination.total || products.length;
    
    // Render products
    productGrid.innerHTML = products.map((product, index) => `
        <div class="product-card-gallery scroll-fade-in bg-white rounded-2xl overflow-hidden shadow-lg" 
             style="transition-delay: ${index * 50}ms;" 
             data-product-id="${product.id}">
            <div class="product-image-wrapper relative h-80">
                ${product.image ? 
                    `<img src="uploads/${product.image}" alt="${product.name}" class="w-full h-full object-cover">` :
                    `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                        <i class="fas fa-crown text-yellow-400 text-7xl opacity-20"></i>
                    </div>`
                }
                
                <!-- Badges Container -->
                <div class="absolute top-2 right-2 flex flex-col gap-2">
                    ${product.is_reserved ? 
                        `<div class="reserved-badge px-2 py-1 rounded-full text-white text-xs font-semibold shadow-lg" style="background-color: #EF4444;">
                            Reserved
                        </div>` : ''
                    }
                    ${product.is_featured ? 
                        `<div class="featured-badge px-3 py-1 rounded-full text-white text-xs font-bold shadow-lg">
                            ⭐ Featured
                        </div>` : ''
                    }
                </div>
                
                <div class="product-overlay">
                    <a href="products/show/${product.id}" 
                       class="flex items-center gap-2 bg-white text-gray-900 px-5 py-2 rounded-lg font-semibold hover:bg-yellow-400 hover:text-white transition-all shadow-lg transform hover:scale-105">
                        <i class="fas fa-eye"></i>
                        Quick View
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="font-serif-elegant text-xl font-bold text-gray-900 mb-2 line-clamp-1">
                    ${product.name}
                </h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                    ${product.description || ''}
                </p>
                
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <span class="text-xs text-gray-500 block">Starting at</span>
                        <span class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-700 bg-clip-text text-transparent">
                            ₱${parseFloat(product.price).toFixed(2)}
                        </span>
                    </div>
                    <div class="flex items-center gap-1 text-yellow-500">
                        <i class="fas fa-star text-sm"></i>
                        <i class="fas fa-star text-sm"></i>
                        <i class="fas fa-star text-sm"></i>
                        <i class="fas fa-star text-sm"></i>
                        <i class="fas fa-star text-sm"></i>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="products/show/${product.id}" 
                       class="flex-1 text-center px-4 py-3 border-2 border-yellow-600 text-yellow-600 rounded-lg hover:bg-yellow-600 hover:text-white transition-all font-semibold">
                        Details
                    </a>
                    <a href="products/show/${product.id}" 
                       class="flex-1 text-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all font-semibold shadow-lg">
                        Rent Now
                    </a>
                </div>
            </div>
        </div>
    `).join('');
    
    // Render pagination
    if (pagination && pagination.total_pages > 1) {
        let paginationHTML = '<div class="flex items-center justify-center gap-2">';
        
        for (let p = 1; p <= pagination.total_pages; p++) {
            const isActive = p === pagination.current_page;
            paginationHTML += `
                <a href="#" 
                   onclick="changePage(${p}); return false;"
                   class="pagination-btn px-5 py-3 rounded-lg font-semibold transition-all ${
                       isActive ? 
                       'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg' : 
                       'bg-white text-gray-700 border-2 border-gray-200 hover:border-yellow-400 hover:text-yellow-600'
                   }">
                    ${p}
                </a>
            `;
        }
        
        paginationHTML += '</div>';
        paginationContainer.innerHTML = paginationHTML;
    } else {
        paginationContainer.innerHTML = '';
    }
    
    // Re-initialize scroll animation for new elements
    initScrollAnimation();
}

// Change page
async function changePage(page) {
    currentPage = page;
    await loadProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Open rental modal
function openRentalModal(productId, productName, price) {
    const modal = document.getElementById('rentalModal');
    
    // Reset form
    document.getElementById('rentalForm').reset();
    document.getElementById('rentalProductId').value = productId;
    document.getElementById('rentalItemName').textContent = productName;
    document.getElementById('rentalItemPrice').textContent = parseFloat(price).toFixed(2);
    document.getElementById('dailyRate').textContent = parseFloat(price).toFixed(2);
    
    // Reset states
    isAvailable = false;
    document.getElementById('availabilityStatus').classList.add('hidden');
    document.getElementById('priceBreakdown').classList.add('hidden');
    document.getElementById('availabilityError').classList.remove('show');
    document.getElementById('availabilitySuccess').classList.remove('show');
    document.getElementById('submitRentalBtn').disabled = true;
    
    modal.classList.remove('hidden');
}

// Close rental modal
function closeRentalModal() {
    document.getElementById('rentalModal').classList.add('hidden');
}

// Set minimum dates
function setMinDates() {
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('rentalStartDate');
    const endDateInput = document.getElementById('rentalEndDate');
    
    if (startDateInput) startDateInput.min = today;
    if (endDateInput) endDateInput.min = today;
}

// Initialize date change listeners
function initDateListeners() {
    const startDateInput = document.getElementById('rentalStartDate');
    const endDateInput = document.getElementById('rentalEndDate');
    
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            // Update end date minimum
            endDateInput.min = this.value;
            
            // Check if end date is before start date
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
            
            checkAvailabilityIfReady();
        });
    }
    
    if (endDateInput) {
        endDateInput.addEventListener('change', checkAvailabilityIfReady);
    }
}

// Check if ready to validate availability
function checkAvailabilityIfReady() {
    const startDate = document.getElementById('rentalStartDate').value;
    const endDate = document.getElementById('rentalEndDate').value;
    const productId = document.getElementById('rentalProductId').value;
    
    if (startDate && endDate && productId) {
        checkAvailability();
    }
}

// Check availability via API
async function checkAvailability() {
    const productId = document.getElementById('rentalProductId').value;
    const startDate = document.getElementById('rentalStartDate').value;
    const endDate = document.getElementById('rentalEndDate').value;
    
    // Show checking status
    const statusDiv = document.getElementById('availabilityStatus');
    statusDiv.classList.remove('hidden');
    statusDiv.innerHTML = `
        <div class="availability-badge checking">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Checking availability...</span>
        </div>
    `;
    
    // Hide previous messages
    document.getElementById('availabilityError').classList.remove('show');
    document.getElementById('availabilitySuccess').classList.remove('show');
    document.getElementById('submitRentalBtn').disabled = true;
    
    try {
        const response = await fetch(
            `api/check_availability.php?item_id=${productId}&order_type=rental&start_date=${startDate}&end_date=${endDate}`
        );
        
        const data = await response.json();
        
        if (data.available) {
            // Available
            statusDiv.innerHTML = `
                <div class="availability-badge available">
                    <i class="fas fa-check-circle"></i>
                    <span>Available for selected dates</span>
                </div>
            `;
            
            document.getElementById('availabilitySuccess').classList.add('show');
            document.getElementById('submitRentalBtn').disabled = false;
            isAvailable = true;
            
            // Calculate and show price
            calculatePrice();
        } else {
            // Not available
            statusDiv.innerHTML = `
                <div class="availability-badge unavailable">
                    <i class="fas fa-times-circle"></i>
                    <span>Not available</span>
                </div>
            `;
            
            const errorDiv = document.getElementById('availabilityError');
            document.getElementById('errorText').textContent = data.message || 'This item is already booked for the selected dates.';
            errorDiv.classList.add('show');
            document.getElementById('submitRentalBtn').disabled = true;
            isAvailable = false;
            document.getElementById('priceBreakdown').classList.add('hidden');
        }
    } catch (error) {
        console.error('Availability check error:', error);
        statusDiv.innerHTML = `
            <div class="availability-badge unavailable">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Error checking availability</span>
            </div>
        `;
        
        const errorDiv = document.getElementById('availabilityError');
        document.getElementById('errorText').textContent = 'Could not check availability. Please try again.';
        errorDiv.classList.add('show');
        document.getElementById('submitRentalBtn').disabled = true;
        isAvailable = false;
    }
}

// Calculate price based on dates
function calculatePrice() {
    const startDate = new Date(document.getElementById('rentalStartDate').value);
    const endDate = new Date(document.getElementById('rentalEndDate').value);
    const dailyRate = parseFloat(document.getElementById('dailyRate').textContent);
    
    if (startDate && endDate && dailyRate) {
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include both start and end day
        const total = diffDays * dailyRate;
        
        document.getElementById('numberOfDays').textContent = diffDays;
        document.getElementById('totalPrice').textContent = total.toFixed(2);
        document.getElementById('priceBreakdown').classList.remove('hidden');
    }
}

// Handle form submission
document.getElementById('rentalForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!isAvailable) {
        alert('Please select available dates before proceeding.');
        return;
    }
    
    const submitBtn = document.getElementById('submitRentalBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('rental/create', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Success - redirect to payment or confirmation
            if (result.redirect) {
                window.location.href = result.redirect;
            } else {
                alert('Rental request submitted successfully!');
                closeRentalModal();
            }
        } else {
            // Show error
            const errorDiv = document.getElementById('availabilityError');
            document.getElementById('errorText').textContent = result.message || 'Failed to submit rental request.';
            errorDiv.classList.add('show');
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Submission error:', error);
        alert('An error occurred. Please try again.');
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Show error message
function showError(message) {
    const productsContainer = document.getElementById('productsContainer');
    if (productsContainer) {
        productsContainer.innerHTML = `
            <div class="empty-state bg-white p-16 rounded-2xl shadow-xl text-center">
                <div class="inline-block p-6 bg-gradient-to-br from-red-100 to-red-200 rounded-full mb-6">
                    <i class="fas fa-exclamation-circle text-red-600 text-6xl"></i>
                </div>
                <h3 class="font-serif-elegant text-3xl font-bold text-gray-900 mb-3">Error Loading Products</h3>
                <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">${message}</p>
                <button onclick="window.location.reload()" class="inline-block px-8 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg">
                    Reload Page
                </button>
            </div>
        `;
    } else {
        alert(message);
    }
}

// Close modal on outside click
document.getElementById('rentalModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRentalModal();
    }
});
</script>