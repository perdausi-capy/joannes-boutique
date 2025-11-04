<?php
$pageTitle = ($product['name'] ?? "Product") . " | Joanne's";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap');
    
    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }
    
    .product-gradient {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(184, 148, 31, 0.12) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .product-gradient::before {
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
    
    .image-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .image-card:hover {
        box-shadow: 0 20px 60px rgba(212, 175, 55, 0.2);
    }
    
    .thumbnail {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }
    
    .thumbnail.active {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
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
    
    .related-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(212, 175, 55, 0.15);
    }
    
    .related-card img {
        transition: transform 0.6s ease;
    }
    
    .related-card:hover img {
        transform: scale(1.1);
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
    
    .badge-featured {
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        animation: pulse-badge 2s ease-in-out infinite;
    }
    
    @keyframes pulse-badge {
        0%, 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(212, 175, 55, 0); }
    }
    
    .modal-overlay {
        backdrop-filter: blur(8px);
    }
    
    .input-enhanced {
        transition: all 0.3s ease;
    }
    
    .input-enhanced:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        transform: translateY(-2px);
    }
</style>

<div class="product-gradient min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($product)): ?>
            <div class="bg-white p-16 rounded-2xl shadow-xl text-center fade-in">
                <i class="fas fa-exclamation-circle text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-serif-elegant font-bold text-gray-900 mb-2">Product Not Found</h2>
                <p class="text-gray-600 mb-6">The product you're looking for doesn't exist or has been removed.</p>
                <a href="products" class="inline-block px-8 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Gallery
                </a>
            </div>
        <?php else: ?>
            <!-- Breadcrumb -->
            <div class="mb-8 fade-in">
                <nav class="flex items-center space-x-2 text-sm text-gray-600">
                    <a href="/" class="hover:text-yellow-600 transition-colors">Home</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="products" class="hover:text-yellow-600 transition-colors">Gallery</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900 font-medium"><?php echo htmlspecialchars($product['name']); ?></span>
                </nav>
            </div>

            <!-- Error Message Display -->
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-red-800 font-semibold"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600 ml-4">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <!-- Image Gallery -->
                <div class="fade-in">
                    <?php 
                        $images = [];
                        if (!empty($product['image'])) { $images[] = $product['image']; }
                        if (!empty($productImages)) {
                            foreach ($productImages as $pi) { $images[] = $pi['filename']; }
                        }
                    ?>
                    <?php if (!empty($images)): ?>
                        <script>
                            window.productImages = <?php echo json_encode(array_values($images)); ?>;
                        </script>
                        <div x-data="{ idx: 0, imgs: window.productImages }" class="space-y-4">
                            <!-- Main Image -->
                            <div class="image-card bg-white rounded-2xl shadow-xl overflow-hidden relative">
                                <div class="aspect-w-1 aspect-h-1 bg-gradient-to-br from-gray-50 to-gray-100">
                                    <img :src="'uploads/' + imgs[idx]" 
                                         :alt="'Product Image'" 
                                         class="w-full h-[600px] object-cover transition-opacity duration-300"
                                         @load="$el.style.opacity = '1'"
                                         style="opacity: 0;">
                                </div>
                                
                                <!-- Navigation Arrows -->
                                <button @click="idx = (idx - 1 + imgs.length) % imgs.length" 
                                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition-all hover:scale-110">
                                    <i class="fas fa-chevron-left text-gray-900"></i>
                                </button>
                                <button @click="idx = (idx + 1) % imgs.length" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition-all hover:scale-110">
                                    <i class="fas fa-chevron-right text-gray-900"></i>
                                </button>
                                
                                <!-- Image Counter -->
                                <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    <span x-text="idx + 1"></span> / <span x-text="imgs.length"></span>
                                </div>
                            </div>
                            
                            <!-- Thumbnails -->
                            <div class="grid grid-cols-5 gap-3">
                                <template x-for="(thumb, t) in imgs" :key="t">
                                    <div @click="idx = t" 
                                         class="thumbnail rounded-lg overflow-hidden border-2 border-transparent"
                                         :class="{ 'active': idx === t }">
                                        <img :src="'uploads/' + thumb" 
                                             class="w-full h-24 object-cover bg-white"
                                             :alt="'Thumbnail ' + (t + 1)">
                                    </div>
                                </template>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="image-card bg-white rounded-2xl shadow-xl h-[600px] flex items-center justify-center">
                            <i class="fas fa-crown text-yellow-400 text-9xl opacity-20"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="fade-in" style="animation-delay: 0.2s;">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <?php if ($product['is_featured']): ?>
                            <div class="badge-featured inline-flex items-center px-4 py-2 rounded-full text-white text-sm font-bold mb-4 shadow-lg">
                                <i class="fas fa-star mr-2"></i>
                                Featured Product
                            </div>
                        <?php endif; ?>
                        
                        <h1 class="font-serif-elegant text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="text-4xl font-bold text-yellow-600">
                                ₱<?php echo number_format($product['price'], 2); ?>
                            </div>
                            <div class="flex items-center gap-1">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="fas fa-star text-yellow-400"></i>
                                <?php endfor; ?>
                                <span class="text-gray-600 text-sm ml-2">(5.0)</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 py-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-600 leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available.')); ?>
                            </p>
                        </div>
                        
                        <div class="border-t border-gray-200 py-6 mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                                    <div class="text-sm text-gray-600 mb-1">Category</div>
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($product['category_name'] ?? 'RENTAL'); ?></div>
                                </div>
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                                    <div class="text-sm text-gray-600 mb-1">Availability</div>
                                    <div class="font-semibold text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <?php if (isset($currentOrder) && $currentOrder): ?>
                                <!-- Payment Section for Current Order -->
                                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border-2 border-yellow-200 shadow-lg">
                                    <h3 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <i class="fas fa-receipt text-yellow-600"></i>
                                        Payment Details
                                    </h3>
                                    
                                    <div class="space-y-3 mb-4">
                                        <div class="flex justify-between items-center py-2 border-b border-yellow-200">
                                            <span class="text-gray-700 font-semibold">Rental Fee:</span>
                                            <span class="text-lg font-bold text-gray-900">₱<?php echo number_format($currentOrder['total_amount'], 2); ?></span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center py-2 border-b border-yellow-200">
                                            <span class="text-gray-700">Rental Period:</span>
                                            <span class="text-gray-900 font-medium">
                                                <?php 
                                                echo date('M d', strtotime($currentOrder['rental_start'])) . ' - ' . 
                                                     date('M d, Y', strtotime($currentOrder['rental_end'])); 
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center py-3 bg-yellow-100 rounded-lg px-4 border-2 border-yellow-300">
                                            <span class="text-lg font-bold text-gray-900">Total Amount:</span>
                                            <span class="text-2xl font-bold text-yellow-700">
                                                ₱<?php echo number_format($currentOrder['total_amount'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php if (isset($_GET['paid']) && $_GET['paid'] == '1'): ?>
                                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-4">
                                            <p class="text-green-800 font-semibold flex items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                Payment submitted successfully! Your booking is confirmed.
                                            </p>
                                        </div>
                                    <?php elseif (($currentOrder['payment_status'] ?? '') !== 'paid'): ?>
                                        <!-- Payment Form -->
                                        <form method="POST" action="<?php echo BASE_URL; ?>payment/process" enctype="multipart/form-data" class="space-y-4">
                                            <input type="hidden" name="order_id" value="<?php echo $currentOrder['order_id']; ?>">
                                            
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    <i class="fas fa-hashtag text-yellow-600 mr-1"></i>
                                                    Reference Number *
                                                </label>
                                                <input type="text" name="reference_number" required
                                                       placeholder="Enter payment reference number"
                                                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    <i class="fas fa-image text-yellow-600 mr-1"></i>
                                                    Payment Proof (Image) *
                                                </label>
                                                <input type="file" name="proof_image" required accept="image/*"
                                                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-500">
                                                <p class="text-xs text-gray-500 mt-1">Upload a screenshot or photo of your payment receipt</p>
                                            </div>
                                            
                                            <button type="submit" 
                                                    class="w-full btn-gold-gradient px-8 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl mt-4">
                                                <span class="flex items-center justify-center gap-2">
                                                    <i class="fas fa-credit-card text-xl"></i>
                                                    Pay ₱<?php echo number_format($currentOrder['total_amount'], 2); ?> Now
                                                </span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                                            <p class="text-green-800 font-semibold flex items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                Payment Completed
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <button onclick="openRentalModal(<?php echo (int)$product['id']; ?>, <?php echo htmlspecialchars(json_encode($product['name']), ENT_QUOTES); ?>, <?php echo (float)$product['price']; ?>)" 
                                        class="w-full btn-gold-gradient px-8 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                                    <span class="flex items-center justify-center gap-2">
                                        <i class="fas fa-calendar-check text-xl"></i>
                                        Rent This Item
                                    </span>
                                </button>
                            <?php endif; ?>
                            
                            <!-- Availability Message Container -->
                            <div id="availability-error-main" class="hidden"></div>
                            
                            <div class="flex items-center justify-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-yellow-600"></i>
                                    Secure Payment
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-truck text-yellow-600"></i>
                                    Fast Delivery
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-star text-yellow-600"></i>
                                    Quality Assured
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php if (!empty($relatedProducts)): ?>
                <div class="fade-in" style="animation-delay: 0.4s;">
                    <div class="text-center mb-12">
                        <span class="text-yellow-600 font-semibold text-sm tracking-wider uppercase mb-4 inline-block">You Might Also Like</span>
                        <h2 class="font-serif-elegant text-4xl font-bold text-gray-900">Related Products</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php foreach ($relatedProducts as $rp): ?>
                            <a href="products/show/<?php echo (int)$rp['id']; ?>" class="related-card bg-white rounded-2xl shadow-lg overflow-hidden group">
                                <div class="relative h-64 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                    <?php if (!empty($rp['image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($rp['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($rp['name']); ?>" 
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-crown text-yellow-400 text-6xl opacity-20"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                </div>
                                <div class="p-6">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1 group-hover:text-yellow-600 transition-colors">
                                        <?php echo htmlspecialchars($rp['name']); ?>
                                    </h3>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-yellow-600">
                                            ₱<?php echo number_format($rp['price'], 2); ?>
                                        </span>
                                        <div class="flex items-center gap-1">
                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Enhanced Rental Modal -->
<div id="rentalModal" class="modal-overlay fixed inset-0 bg-black/60 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <h3 class="text-xl font-serif-elegant font-bold text-white">Book Your Rental</h3>
                    </div>
                    <button onclick="closeRentalModal()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="rentalForm" method="post" action="<?php echo BASE_URL; ?>rental/create" class="p-6 space-y-5">
                <input type="hidden" name="product_id" id="rentalProductId">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                    <label class="block text-xs text-yellow-700 font-semibold mb-1 uppercase tracking-wide">Selected Item</label>
                    <div class="font-bold text-lg font-serif-elegant text-gray-900" id="rentalItemName"></div>
                    <div class="text-yellow-600 font-semibold mt-1">₱<span id="rentalItemPrice"></span> per day</div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-yellow-600 mr-1"></i>
                            Start Date *
                        </label>
                        <input type="date" name="rental_start" required 
                               class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-yellow-600 mr-1"></i>
                            End Date *
                        </label>
                        <input type="date" name="rental_end" required 
                               class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                    </div>
                </div>
                
                <!-- <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-ruler text-yellow-600 mr-1"></i>
                        Size (Optional)
                    </label>
                    <input type="text" name="size" placeholder="e.g., Small, Medium, Large" 
                           class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                </div> -->
                
                <div class="border-t-2 border-gray-100 pt-5">
                    <h4 class="font-serif-elegant font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-circle text-yellow-600 text-xl"></i>
                        Contact Information
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="contact_name" required 
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="contact_email" required 
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone *</label>
                            <input type="tel" name="contact_phone" required 
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                        </div>
                    </div>
                </div>
                
                <!-- Availability error in modal -->
                <div id="availability-error-modal" style="display: none;"></div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeRentalModal()" 
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 font-semibold shadow-lg transition-all">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRentalModal(productId, productName, productPrice) {
        const modal = document.getElementById('rentalModal');
        const productIdField = document.getElementById('rentalProductId');
        const itemNameField = document.getElementById('rentalItemName');
        const itemPriceField = document.getElementById('rentalItemPrice');
        
        if (!modal || !productIdField || !itemNameField || !itemPriceField) {
            console.error('Modal elements not found');
            return;
        }
        
        productIdField.value = productId;
        itemNameField.textContent = productName;
        itemPriceField.textContent = productPrice.toFixed(2);
        modal.classList.remove('hidden');
        
        // Clear any previous availability errors when opening modal
        hideAvailabilityError();
    }

    function closeRentalModal() {
        const modal = document.getElementById('rentalModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        // Clear any availability errors when closing modal
        hideAvailabilityError();
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('rentalModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRentalModal();
                }
            });
        }
        
        // Add real-time availability checking for rental form
        const rentalForm = document.getElementById('rentalForm');
        if (rentalForm) {
            const startDateInput = rentalForm.querySelector('input[name="rental_start"]');
            const endDateInput = rentalForm.querySelector('input[name="rental_end"]');
            const submitButton = rentalForm.querySelector('button[type="submit"]');
            
            let availabilityCheckTimeout = null;
            
            function checkAvailabilityRealTime() {
                // Clear previous timeout
                if (availabilityCheckTimeout) {
                    clearTimeout(availabilityCheckTimeout);
                }
                
                // Debounce: wait 500ms after user stops typing
                availabilityCheckTimeout = setTimeout(async function() {
                    const productId = document.getElementById('rentalProductId')?.value;
                    const startDate = startDateInput?.value;
                    const endDate = endDateInput?.value;
                    
                    // Reset UI state
                    hideAvailabilityError();
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                    
                    // Validate inputs
                    if (!productId || !startDate || !endDate) {
                        return;
                    }
                    
                    // Validate date logic
                    if (new Date(startDate) > new Date(endDate)) {
                        showAvailabilityError('Start date must be before end date.');
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                        return;
                    }
                    
                    // Validate dates not in past
                    if (new Date(startDate) < new Date().setHours(0, 0, 0, 0)) {
                        showAvailabilityError('Start date cannot be in the past.');
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                        return;
                    }
                    
                    // Check availability via API
                    try {
                        console.log('Checking availability for item_id:', productId, 'dates:', startDate, 'to', endDate);
                        
                        const apiUrl = `<?php echo BASE_URL; ?>api/check_availability?item_id=${productId}&order_type=rental&start_date=${startDate}&end_date=${endDate}`;
                        console.log('API URL:', apiUrl);
                        
                        const response = await fetch(apiUrl);
                        
                        // Check if response is OK
                        if (!response.ok) {
                            console.error('API error: HTTP', response.status);
                            return;
                        }
                        
                        const data = await response.json();
                        
                        console.log('Availability response:', data);
                        
                        // Handle new response format: {available: true/false, message?: string}
                        if (data.available === undefined) {
                            console.error('API error: Invalid response format');
                            return;
                        }
                        
                        if (!data.available) {
                            const errorMsg = data.message || 'This item is already booked on your selected dates.';
                            showAvailabilityError(errorMsg);
                            if (submitButton) {
                                submitButton.disabled = true;
                                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                            }
                        } else {
                            hideAvailabilityError();
                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                        }
                    } catch (error) {
                        console.error('Error checking availability:', error);
                        // Don't block submission on API error - backend will validate
                    }
                }, 500);
            }
            
            // Attach event listeners
            if (startDateInput) {
                startDateInput.addEventListener('change', checkAvailabilityRealTime);
                startDateInput.addEventListener('input', checkAvailabilityRealTime);
            }
            if (endDateInput) {
                endDateInput.addEventListener('change', checkAvailabilityRealTime);
                endDateInput.addEventListener('input', checkAvailabilityRealTime);
            }
        }
    });
    
    // Availability error display functions
    function showAvailabilityError(message) {
        // Show on main page
        const mainErrorDiv = document.getElementById('availability-error-main');
        if (mainErrorDiv) {
            mainErrorDiv.className = 'p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md';
            mainErrorDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-red-800 font-semibold">${message}</p>
                    </div>
                    <button onclick="hideAvailabilityError()" class="text-red-500 hover:text-red-700 ml-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            mainErrorDiv.classList.remove('hidden');
            
            // Scroll to the error message
            mainErrorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Also show in modal (for users who have modal open)
        const modalErrorDiv = document.getElementById('availability-error-modal');
        if (modalErrorDiv) {
            modalErrorDiv.className = 'mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md';
            modalErrorDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-red-800 font-semibold text-sm">${message}</p>
                    </div>
                </div>
            `;
            modalErrorDiv.style.display = 'block';
        }
    }
    
    function hideAvailabilityError() {
        // Hide on main page
        const mainErrorDiv = document.getElementById('availability-error-main');
        if (mainErrorDiv) {
            mainErrorDiv.classList.add('hidden');
            mainErrorDiv.innerHTML = '';
        }
        
        // Hide in modal
        const modalErrorDiv = document.getElementById('availability-error-modal');
        if (modalErrorDiv) {
            modalErrorDiv.style.display = 'none';
            modalErrorDiv.innerHTML = '';
        }
    }
</script>