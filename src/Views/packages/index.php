<?php $pageTitle = "Packages | Joanne's"; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');
    
    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }
    
    .packages-hero-gradient {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        position: relative;
        overflow: hidden;
    }
    
    .packages-hero-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
        animation: pulse 8s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    .package-card-enhanced {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    .package-card-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
        transition: left 0.5s;
        z-index: 1;
        pointer-events: none;
    }
    
    .package-card-enhanced:hover::before {
        left: 100%;
    }
    
    .package-card-enhanced:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 60px rgba(212, 175, 55, 0.25);
    }
    
    .package-card-enhanced .package-image {
        height: 280px;
        overflow: hidden;
        position: relative;
    }
    
    .package-card-enhanced .package-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .package-card-enhanced:hover .package-image img {
        transform: scale(1.1);
    }
    
    .package-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        z-index: 2;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
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
    
    .btn-outline-gold {
        border: 2px solid #d4af37;
        color: #d4af37;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-outline-gold::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
        transition: left 0.3s;
        z-index: 0;
    }
    
    .btn-outline-gold:hover::before {
        left: 0;
    }
    
    .btn-outline-gold:hover {
        color: white;
        border-color: #d4af37;
    }
    
    .btn-outline-gold span {
        position: relative;
        z-index: 1;
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
    
    .inclusion-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.813rem;
        font-weight: 500;
        color: #92400e;
    }
    
    .empty-state {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 24px;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .modal-backdrop {
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-content {
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Hero Section -->
<section class="packages-hero-gradient py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center text-white space-y-6">
            <div class="inline-block px-4 py-2 bg-white/10 rounded-full backdrop-blur-sm border border-white/20 mb-4">
                <span class="text-yellow-300 text-sm font-medium">✨ Exclusive Packages</span>
            </div>
            
            <h1 class="font-serif-elegant text-5xl md:text-6xl font-bold leading-tight">
                Wedding & Event
                <span class="block text-yellow-400">Packages</span>
            </h1>
            
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Discover our carefully curated packages designed to make your special day unforgettable. From intimate ceremonies to grand celebrations.
            </p>
        </div>
    </div>
</section>

<!-- Packages Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($packages ?? [])): ?>
            <div class="empty-state scroll-reveal">
                <div class="inline-block w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-box-open text-yellow-600 text-4xl"></i>
                </div>
                <h2 class="font-serif-elegant text-3xl font-bold text-gray-800 mb-3">No Packages Available</h2>
                <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                    We're currently updating our package offerings. Please check back soon for our exclusive deals!
                </p>
                <a href="contact" class="inline-block px-8 py-4 btn-gold-elegant text-white rounded-full font-semibold hover:shadow-2xl">
                    <span>Contact Us for Custom Packages</span>
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($packages as $p): ?>
                    <div class="package-card-enhanced scroll-reveal">
                        <div class="package-image">
                            <?php if (!empty($p['background_image'])): ?>
                                <img src="<?php echo rtrim(BASE_URL, '/') . '/uploads/' . htmlspecialchars($p['background_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($p['package_name']); ?>">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-crown text-yellow-400 text-6xl opacity-30"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($p['price'])): ?>
                                <div class="package-badge">
                                    ₱<?php echo number_format($p['price'], 2); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div>
                                <h2 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-2">
                                    <?php echo htmlspecialchars($p['package_name']); ?>
                                </h2>
                                
                                <?php if (!empty($p['hotel_name'])): ?>
                                    <div class="flex items-center gap-2 text-gray-600 mb-1">
                                        <i class="fas fa-hotel text-yellow-600"></i>
                                        <span class="font-medium"><?php echo htmlspecialchars($p['hotel_name']); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($p['hotel_address'])): ?>
                                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                                        <i class="fas fa-map-marker-alt text-yellow-600"></i>
                                        <span><?php echo htmlspecialchars($p['hotel_address']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($p['hotel_description'])): ?>
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    <?php echo htmlspecialchars($p['hotel_description']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php $inc = json_decode($p['inclusions'] ?? '{}', true) ?: []; ?>
                            <?php if (!empty($inc)): ?>
                                <div class="space-y-3 pt-2 border-t border-gray-100">
                                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                                        <i class="fas fa-check-circle text-yellow-600"></i>
                                        Package Includes:
                                    </h3>
                                    <div class="space-y-2">
                                        <?php $shown = 0; foreach ($inc as $label => $items): if ($shown >= 2) break; if (empty($items)) continue; $shown++; ?>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">
                                                    <?php echo htmlspecialchars($label); ?>
                                                </p>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php $i = 0; foreach ($items as $it): if ($i >= 3) break; $i++; ?>
                                                        <span class="inclusion-badge">
                                                            <i class="fas fa-check text-xs"></i>
                                                            <?php echo htmlspecialchars($it); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex gap-2 pt-4">
                                <a href="packages/view/<?php echo (int)$p['package_id']; ?>" 
                                   class="flex-1 text-center px-4 py-3 btn-outline-gold rounded-lg font-semibold">
                                    <span>View Details</span>
                                </a>
                                <button onclick="openBookingModal(<?php echo (int)$p['package_id']; ?>, '<?php echo htmlspecialchars($p['package_name'], ENT_QUOTES); ?>', <?php echo $p['price'] ?? 0; ?>)" 
                                        class="flex-1 px-4 py-3 btn-gold-elegant text-white rounded-lg font-semibold shadow-lg">
                                    <span>Book Now</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-gray-900 to-gray-800 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-serif-elegant text-4xl md:text-5xl font-bold mb-6">Need a Custom Package?</h2>
        <p class="text-xl text-gray-300 mb-8">
            Let us create a personalized package tailored to your unique vision and requirements.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="contact" class="px-10 py-5 btn-gold-elegant text-white rounded-full font-semibold text-lg hover:shadow-2xl">
                <span>Contact Our Team</span>
            </a>
            <a href="products" class="px-10 py-5 rounded-full text-white font-semibold border-2 border-white/30 hover:bg-white/10 transition-all text-lg">
                Browse Products
            </a>
        </div>
    </div>
</section>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black/60 hidden z-50 modal-backdrop" x-data="bookingModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto modal-content">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-serif-elegant text-2xl font-bold text-gray-900">Book Your Package</h3>
                    <button @click="closeModal()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
                
                <form method="POST" action="package/book" class="space-y-5">
                    <input type="hidden" name="package_id" x-model="form.package_id">
                    
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                        <label class="block text-xs font-semibold text-yellow-800 mb-1 uppercase tracking-wide">Selected Package</label>
                        <p class="font-serif-elegant text-xl font-bold text-gray-900" x-text="form.package_name"></p>
                    </div>
                    
                    <div>
                        <label for="event_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-yellow-600 mr-1"></i>
                            Event Date *
                        </label>
                        <input type="date" id="event_date" name="event_date" 
                               x-model="form.event_date" 
                               @change="checkPackageAvailability()"
                               @input="checkPackageAvailability()"
                               :min="new Date().toISOString().split('T')[0]"
                               required
                               class="w-full px-4 py-3 border-2 rounded-lg focus:outline-none transition-colors"
                               :class="availabilityError ? 'border-red-300 focus:border-red-500' : 'border-gray-200 focus:border-yellow-500'">
                        
                        <!-- Availability Status -->
                        <div x-show="isCheckingAvailability" class="mt-2 text-sm text-blue-600">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Checking availability...
                        </div>
                        
                        <!-- Availability Error -->
                        <div x-show="availabilityError" class="mt-2 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <p class="text-red-800 font-semibold text-sm" x-text="availabilityError"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="number_of_guests" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users text-yellow-600 mr-1"></i>
                            Number of Guests *
                        </label>
                        <input type="number" id="number_of_guests" name="number_of_guests" x-model="form.number_of_guests" min="1" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-yellow-500 transition-colors">
                    </div>
                    
                    <div class="border-t-2 border-gray-100 pt-5">
                        <h4 class="font-serif-elegant text-lg font-bold text-gray-900 mb-4">Contact Information</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="contact_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-yellow-600 mr-1"></i>
                                    Full Name *
                                </label>
                                <input type="text" id="contact_name" name="contact_name" x-model="form.contact_name" required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-yellow-500 transition-colors">
                            </div>
                            <div>
                                <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-yellow-600 mr-1"></i>
                                    Email Address *
                                </label>
                                <input type="email" id="contact_email" name="contact_email" x-model="form.contact_email" required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-yellow-500 transition-colors">
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-yellow-600 mr-1"></i>
                                    Phone Number *
                                </label>
                                <input type="tel" id="contact_phone" name="contact_phone" x-model="form.contact_phone" required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-yellow-500 transition-colors">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl border border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-600">Total Package Price:</span>
                            <span class="font-serif-elegant text-3xl font-bold text-yellow-600" x-text="'₱' + form.price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closeModal()" 
                                class="flex-1 px-6 py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                :disabled="!isAvailable || isCheckingAvailability || !!availabilityError"
                                :class="(!isAvailable || isCheckingAvailability || !!availabilityError) ? 'opacity-50 cursor-not-allowed' : ''"
                                class="flex-1 px-6 py-4 btn-gold-elegant text-white rounded-lg font-semibold shadow-lg transition-all">
                            <span>Proceed to Payment</span>
                        </button>
                    </div>
                </form>
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

function openBookingModal(packageId, packageName, price) {
    const modal = document.getElementById('bookingModal');
    const alpineData = Alpine.$data(modal);
    alpineData.openModal(packageId, packageName, price);
}

function bookingModal() {
    return {
        form: {
            package_id: '',
            package_name: '',
            price: 0,
            event_date: '',
            number_of_guests: 1,
            contact_name: '',
            contact_email: '',
            contact_phone: ''
        },
        availabilityCheckTimeout: null,
        availabilityError: '',
        isCheckingAvailability: false,
        isAvailable: true,
        
        async checkPackageAvailability() {
            // Clear previous timeout
            if (this.availabilityCheckTimeout) {
                clearTimeout(this.availabilityCheckTimeout);
            }
            
            // Reset state
            this.availabilityError = '';
            this.isAvailable = true;
            
            if (!this.form.package_id || !this.form.event_date) {
                return;
            }
            
            // Validate date not in past
            const selectedDate = new Date(this.form.event_date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                this.availabilityError = 'Event date cannot be in the past.';
                this.isAvailable = false;
                return;
            }
            
            // Debounce: wait 500ms after user stops changing date
            this.availabilityCheckTimeout = setTimeout(async () => {
                this.isCheckingAvailability = true;
                
                try {
                    const packageId = this.form.package_id;
                    const eventDate = this.form.event_date;
                    
                    console.log('Checking package availability for item_id:', packageId, 'event_date:', eventDate);
                    
                    const apiUrl = `<?php echo BASE_URL; ?>api/check_availability?item_id=${packageId}&order_type=package&start_date=${eventDate}`;
                    console.log('API URL:', apiUrl);
                    
                    const response = await fetch(apiUrl);
                    
                    // Check if response is OK
                    if (!response.ok) {
                        console.error('API error: HTTP', response.status);
                        this.isCheckingAvailability = false;
                        return;
                    }
                    
                    const data = await response.json();
                    
                    console.log('Package availability response:', data);
                    
                    // Handle new response format: {available: true/false, reason?: string}
                    if (data.available === undefined) {
                        console.error('API error: Invalid response format');
                        this.isCheckingAvailability = false;
                        return;
                    }
                    
                    if (!data.available) {
                        const errorMsg = data.message || `This package is already booked for ${new Date(eventDate).toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})}. Please choose a different date.`;
                        this.availabilityError = errorMsg;
                        this.isAvailable = false;
                    } else {
                        this.availabilityError = '';
                        this.isAvailable = true;
                    }
                } catch (error) {
                    console.error('Error checking package availability:', error);
                    // Don't block submission on API error - backend will validate
                } finally {
                    this.isCheckingAvailability = false;
                }
            }, 500);
        },
        
        openModal(packageId, packageName, price) {
            this.form = {
                package_id: packageId,
                package_name: packageName,
                price: price,
                event_date: '',
                number_of_guests: 1,
                contact_name: '',
                contact_email: '',
                contact_phone: ''
            };
            this.availabilityError = '';
            this.isAvailable = true;
            document.getElementById('bookingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        },
        
        closeModal() {
            if (this.availabilityCheckTimeout) {
                clearTimeout(this.availabilityCheckTimeout);
            }
            document.getElementById('bookingModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    };
}

// Close modal on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modal = document.getElementById('bookingModal');
        if (modal && !modal.classList.contains('hidden')) {
            const alpineData = Alpine.$data(modal);
            alpineData.closeModal();
        }
    }
});
</script>