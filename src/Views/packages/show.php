<?php $pageTitle = "Package Details | Joanne's"; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');
    
    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }
    
    .details-hero-image {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }
    
    .details-hero-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        pointer-events: none;
    }
    
    .details-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .details-hero-image:hover img {
        transform: scale(1.05);
    }
    
    .price-badge-large {
        position: absolute;
        top: 30px;
        right: 30px;
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        color: white;
        padding: 16px 32px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.5rem;
        z-index: 10;
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .info-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(212, 175, 55, 0.15);
    }
    
    .inclusion-card {
        background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .inclusion-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.05), transparent);
        transition: left 0.5s;
    }
    
    .inclusion-card:hover::before {
        left: 100%;
    }
    
    .inclusion-card:hover {
        transform: translateY(-8px);
        border-color: rgba(212, 175, 55, 0.3);
        box-shadow: 0 15px 50px rgba(212, 175, 55, 0.2);
    }
    
    .inclusion-card h2 {
        position: relative;
        padding-left: 3rem;
    }
    
    .inclusion-icon {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
        font-size: 1.25rem;
    }
    
    .inclusion-card ul li {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 0.75rem;
    }
    
    .inclusion-card ul li::before {
        content: '✓';
        position: absolute;
        left: 0;
        top: 0;
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.75rem;
    }
    
    .btn-gold-elegant {
        background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-gold-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
        transition: left 0.3s;
    }
    
    .btn-gold-elegant:hover::before {
        left: 0;
    }
    
    .btn-gold-elegant span {
        position: relative;
        z-index: 1;
    }
    
    .btn-outline-elegant {
        border: 2px solid rgba(107, 114, 128, 0.3);
        color: #4b5563;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-outline-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: #f9fafb;
        transition: left 0.3s;
        z-index: 0;
    }
    
    .btn-outline-elegant:hover::before {
        left: 0;
    }
    
    .btn-outline-elegant:hover {
        border-color: #d4af37;
        color: #d4af37;
    }
    
    .btn-outline-elegant span {
        position: relative;
        z-index: 1;
    }
    
    .divider-elegant {
        height: 2px;
        background: linear-gradient(to right, transparent, #d4af37, transparent);
        margin: 3rem 0;
    }
    
    .scroll-reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease;
    }
    
    .scroll-reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }
    
    .breadcrumb a {
        color: #d4af37;
        transition: color 0.2s;
    }
    
    .breadcrumb a:hover {
        color: #b8941f;
    }
    
    .sticky-booking {
        position: sticky;
        top: 2rem;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        color: white;
    }
    
    .feature-highlight {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(212, 175, 55, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #d4af37;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }
</style>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Error Message Display -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md scroll-reveal">
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

        <!-- Breadcrumb -->
        <nav class="breadcrumb scroll-reveal">
            <a href="/">Home</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="packages">Packages</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium"><?php echo htmlspecialchars($package['package_name']); ?></span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Hero Image -->
                <div class="details-hero-image h-96 scroll-reveal">
                    <?php if (!empty($package['background_image'])): ?>
                        <img src="<?php echo rtrim(BASE_URL, '/') . '/uploads/' . htmlspecialchars($package['background_image']); ?>" 
                             alt="<?php echo htmlspecialchars($package['package_name']); ?>">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <i class="fas fa-crown text-yellow-400 text-8xl opacity-30"></i>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($package['price'])): ?>
                        <div class="price-badge-large">
                            ₱<?php echo number_format($package['price'], 2); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Package Info -->
                <div class="info-card scroll-reveal">
                    <div class="space-y-4">
                        <div>
                            <h1 class="font-serif-elegant text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                                <?php echo htmlspecialchars($package['package_name']); ?>
                            </h1>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="feature-highlight">
                                    <i class="fas fa-star"></i>
                                    Premium Package
                                </span>
                                <span class="feature-highlight">
                                    <i class="fas fa-clock"></i>
                                    Flexible Scheduling
                                </span>
                            </div>
                        </div>
                        
                        <?php if (!empty($package['hotel_name'])): ?>
                            <div class="flex items-start gap-3 py-3 border-t border-gray-100">
                                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-hotel text-yellow-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Venue</p>
                                    <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($package['hotel_name']); ?></p>
                                    <?php if (!empty($package['hotel_address'])): ?>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-map-marker-alt text-yellow-600 mr-1"></i>
                                            <?php echo htmlspecialchars($package['hotel_address']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($package['hotel_description'])): ?>
                            <div class="pt-4 border-t border-gray-100">
                                <h3 class="font-serif-elegant text-xl font-bold text-gray-900 mb-3">About This Package</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    <?php echo nl2br(htmlspecialchars($package['hotel_description'])); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Divider -->
                <div class="divider-elegant"></div>

                <!-- Inclusions -->
                <div class="scroll-reveal">
                    <div class="mb-8">
                        <h2 class="font-serif-elegant text-3xl md:text-4xl font-bold text-gray-900 mb-2">Package Inclusions</h2>
                        <p class="text-gray-600">Everything you need for a perfect celebration</p>
                    </div>
                    
                    <?php $inc = json_decode($package['inclusions'] ?? '{}', true) ?: []; ?>
                    <?php if (!empty($inc)): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php 
                            $iconMap = [
                                'gown' => 'fas fa-tshirt',
                                'suit' => 'fas fa-user-tie',
                                'attire' => 'fas fa-tshirt',
                                'venue' => 'fas fa-building',
                                'catering' => 'fas fa-utensils',
                                'food' => 'fas fa-utensils',
                                'decoration' => 'fas fa-palette',
                                'photography' => 'fas fa-camera',
                                'entertainment' => 'fas fa-music',
                                'coordination' => 'fas fa-clipboard-list'
                            ];
                            
                            foreach ($inc as $label => $items): 
                                if (empty($items)) continue;
                                
                                // Find matching icon
                                $icon = 'fas fa-check-circle';
                                foreach ($iconMap as $key => $iconClass) {
                                    if (stripos($label, $key) !== false) {
                                        $icon = $iconClass;
                                        break;
                                    }
                                }
                            ?>
                                <div class="inclusion-card">
                                    <h2 class="font-serif-elegant text-xl font-bold text-gray-900 mb-4">
                                        <span class="inclusion-icon">
                                            <i class="<?php echo $icon; ?>"></i>
                                        </span>
                                        <?php echo htmlspecialchars($label); ?>
                                    </h2>
                                    <ul class="space-y-2">
                                        <?php foreach ($items as $it): ?>
                                            <li class="text-gray-700"><?php echo htmlspecialchars($it); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-200">
                            <i class="fas fa-info-circle text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Package inclusions are being updated</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky-booking scroll-reveal">
                    <div class="text-center mb-6">
                        <div class="inline-block px-4 py-2 bg-white/10 rounded-full backdrop-blur-sm border border-white/20 mb-4">
                            <span class="text-yellow-300 text-sm font-medium">✨ Ready to Book?</span>
                        </div>
                        <h3 class="font-serif-elegant text-2xl font-bold mb-2">Make it Unforgettable</h3>
                        <p class="text-gray-400 text-sm">Secure your date and start planning your dream event</p>
                    </div>
                    
                    <?php if (!empty($package['price'])): ?>
                        <div class="bg-white/10 rounded-xl p-4 mb-6 backdrop-blur-sm border border-white/10">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-300 text-sm">Package Price</span>
                                <span class="font-serif-elegant text-3xl font-bold text-yellow-400">
                                    ₱<?php echo number_format($package['price'], 2); ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-sm text-gray-300">
                            <i class="fas fa-check-circle text-yellow-400"></i>
                            <span>Customizable packages available</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-300">
                            <i class="fas fa-check-circle text-yellow-400"></i>
                            <span>Professional coordination included</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-300">
                            <i class="fas fa-check-circle text-yellow-400"></i>
                            <span>Flexible payment terms</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <!-- <a href="booking?package_id=<?php echo (int)$package['package_id']; ?>" 
                           class="block w-full px-6 py-4 btn-gold-elegant text-white rounded-xl font-semibold text-center shadow-lg">
                            <span>
                                <i class="fas fa-calendar-check mr-2"></i>
                                Book This Package
                            </span>
                        </a> -->
                        <a href="packages" 
                           class="block w-full px-6 py-4 btn-outline-elegant rounded-xl font-semibold text-center bg-white">
                            <span>
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Packages
                            </span>
                        </a>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-white/10 text-center">
                        <p class="text-gray-400 text-sm mb-3">Need more information?</p>
                        <a href="contact" class="text-yellow-400 hover:text-yellow-300 text-sm font-semibold transition-colors">
                            <i class="fas fa-phone mr-1"></i>
                            Contact Our Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll reveal animation
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            setTimeout(() => {
                entry.target.classList.add('active');
            }, index * 100);
        }
    });
}, observerOptions);

document.querySelectorAll('.scroll-reveal').forEach(el => {
    observer.observe(el);
});
</script>