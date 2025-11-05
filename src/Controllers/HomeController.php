<?php
class HomeController {
    private $productModel;
    private $testimonialModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->testimonialModel = new Testimonial();
    }
    
    public function index() {
        $featuredProducts = $this->productModel->getFeatured(6);
        $testimonials = $this->testimonialModel->findPublic();
        
        $content = $this->renderHome($featuredProducts, $testimonials);
        $this->render('layout', [
            'content' => $content,
            'pageTitle' => 'Joanne\'s Gown and Suits - Elegance Redefined'
        ]);
    }
    
    private function renderHome($featuredProducts, $testimonials) {
        ob_start();
        ?>
        
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');
            
            .font-serif-elegant {
                font-family: 'Cormorant Garamond', serif;
            }
            
            .hero-gradient-new {
                background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
                position: relative;
                overflow: hidden;
            }
            
            .hero-gradient-new::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                            radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.08) 0%, transparent 50%);
                animation: pulse 8s ease-in-out infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.5; }
                50% { opacity: 1; }
            }
            
            .hero-text {
                animation: fadeInUp 1s ease-out;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                transition: all 0.3s ease;
            }
            
            .glass-card:hover {
                background: rgba(255, 255, 255, 0.1);
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            }
            
            .feature-card {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }
            
            .feature-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
                transition: left 0.5s;
            }
            
            .feature-card:hover::before {
                left: 100%;
            }
            
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 60px rgba(212, 175, 55, 0.2);
            }
            
            .stat-number {
                font-size: 3rem;
                font-weight: 700;
                background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .btn-gold-new {
                background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .btn-gold-new::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
                transition: left 0.3s;
            }
            
            .btn-gold-new:hover::before {
                left: 0;
            }
            
            .btn-gold-new span {
                position: relative;
                z-index: 1;
            }
            
            .floating {
                animation: floating 3s ease-in-out infinite;
            }
            
            @keyframes floating {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }
            
            /* UPDATED: Faster, smoother scroll reveal */
            .scroll-reveal {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.5s ease, transform 0.5s ease;
            }
            
            .scroll-reveal.active {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* UPDATED: Smoother product card animations */
            .product-card-enhanced {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .product-card-enhanced:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 60px rgba(212, 175, 55, 0.3);
            }
            
            .product-card-enhanced img {
                transition: transform 0.5s ease;
            }
            
            .product-card-enhanced:hover img {
                transform: scale(1.05);
            }
            
            .testimonial-card-new {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            
            .testimonial-card-new:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            }
        </style>

        <!-- Hero Section -->
        <section class="hero-gradient-new min-h-screen flex items-center relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="hero-text text-white space-y-6">
                        <div class="inline-block px-4 py-2 bg-white/10 rounded-full backdrop-blur-sm border border-white/20 mb-4">
                            <span class="text-yellow-300 text-sm font-medium">✨ Premium Collection 2025</span>
                        </div>
                        
                        <h1 class="font-serif-elegant text-5xl md:text-7xl font-bold leading-tight">
                            Elegance
                            <span class="block text-yellow-400">Redefined</span>
                        </h1>
                        
                        <p class="text-lg text-gray-300 max-w-xl">
                            Discover exquisite gowns and tailored suits that transform moments into memories. Where sophistication meets artistry.
                        </p>
                        
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="products" class="btn-gold-new px-8 py-4 rounded-full text-white font-semibold hover:shadow-2xl">
                                <span>Explore Collection</span>
                            </a>
                            <a href="packages" class="btn-gold-new px-8 py-4 rounded-full text-white font-semibold border-2 border-white/30 hover:bg-white/10 transition-all">
                                <span>Wedding Packages</span>
                            </a>
                        </div>
                        
                        <div class="flex gap-8 pt-8">
                            <div>
                                <div class="stat-number font-serif-elegant">500+</div>
                                <p class="text-gray-400 text-sm">Happy Clients</p>
                            </div>
                            <div>
                                <div class="stat-number font-serif-elegant">10</div>
                                <p class="text-gray-400 text-sm">Years Experience</p>
                            </div>
                            <div>
                                <div class="stat-number font-serif-elegant">98%</div>
                                <p class="text-gray-400 text-sm">Satisfaction Rate</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative hidden md:block">
                        <div class="floating">
                            <div class="glass-card rounded-3xl p-8 backdrop-blur-lg">
                                <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl h-96 flex items-center justify-center">
                                    <img src="assets/bghome2.jpeg" alt="Joanne's" class="w-full h-full object-cover rounded-2xl">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
                <i class="fas fa-chevron-down text-2xl opacity-50"></i>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 scroll-reveal">
                    <span class="text-yellow-600 font-semibold text-sm tracking-wider uppercase">Why Choose Us</span>
                    <h2 class="font-serif-elegant text-4xl md:text-5xl font-bold text-gray-900 mt-2">Crafted with Excellence</h2>
                    <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Every piece is a masterpiece, designed to make you feel extraordinary</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="feature-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 scroll-reveal">
                        <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-scissors text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-3">Custom Tailoring</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Precision-crafted garments tailored to your exact measurements for the perfect fit.
                        </p>
                    </div>
                    
                    <div class="feature-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 scroll-reveal">
                        <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-gem text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-3">Premium Fabrics</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Only the finest materials sourced from renowned suppliers worldwide.
                        </p>
                    </div>
                    
                    <div class="feature-card bg-white rounded-2xl p-8 shadow-lg border border-gray-100 scroll-reveal">
                        <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-star text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-3">Expert Design</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Award-winning designers bringing your vision to life with artistic flair.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products - FIXED -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 scroll-reveal">
                    <span class="text-yellow-600 font-semibold text-sm tracking-wider uppercase">Collections</span>
                    <h2 class="font-serif-elegant text-4xl md:text-5xl font-bold text-gray-900 mt-2">Featured Collections</h2>
                    <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Handpicked selections showcasing our finest craftsmanship and design excellence</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card-enhanced bg-white rounded-2xl overflow-hidden shadow-lg scroll-reveal">
                        <!-- FIXED: Larger product image that occupies more space -->
                        <div class="relative overflow-hidden aspect-[3/4] bg-gray-50 p-2">
                            <?php if ($product['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="w-full h-full object-contain">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-crown text-yellow-400 text-6xl opacity-30"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-yellow-600">
                                    ₱<?php echo number_format($product['price'], 2); ?>
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="products/show/<?php echo $product['id']; ?>" 
                                   class="flex-1 text-center px-4 py-3 border-2 border-yellow-600 text-yellow-600 rounded-lg hover:bg-yellow-600 hover:text-white transition-all font-semibold">
                                   Rent Now
                                </a>
                                <!-- <button onclick="openRentalModal(<?php echo (int)$product['id']; ?>, <?php echo htmlspecialchars(json_encode($product['name']), ENT_QUOTES); ?>, <?php echo (float)$product['price']; ?>)" 
                                        class="flex-1 px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all font-semibold shadow-lg">
                                    Rent Now
                                </button> -->
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-12 scroll-reveal">
                    <a href="products" 
                       class="inline-block px-10 py-4 border-2 border-yellow-600 text-yellow-600 rounded-full font-semibold hover:bg-yellow-600 hover:text-white transition-all text-lg">
                        View All Products
                    </a>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <?php if (!empty($testimonials)): ?>
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 scroll-reveal">
                    <span class="text-yellow-600 font-semibold text-sm tracking-wider uppercase">Testimonials</span>
                    <h2 class="font-serif-elegant text-4xl md:text-5xl font-bold text-gray-900 mt-2">Loved by Many</h2>
                    <p class="text-gray-600 mt-4">Real experiences from our valued customers</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-card-new scroll-reveal">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <span class="font-serif-elegant font-bold text-yellow-600 text-xl">
                                    <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <div class="flex text-yellow-400 text-sm">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? '' : 'opacity-30'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic leading-relaxed">
                            "<?php echo htmlspecialchars($testimonial['message']); ?>"
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-gray-900 to-gray-800 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
                <h2 class="font-serif-elegant text-4xl md:text-5xl font-bold mb-6">Ready to Look Stunning?</h2>
                <p class="text-xl text-gray-300 mb-8">Book your consultation today and let us create something extraordinary for you.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <!-- <a href="booking" class="btn-gold-new px-10 py-5 rounded-full text-white font-semibold text-lg hover:shadow-2xl">
                        <span>Schedule Appointment</span>
                    </a> -->
                    <a href="contact" class="px-10 py-5 rounded-full text-white font-semibold border-2 border-white/30 hover:bg-white/10 transition-all text-lg">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>

        <script>
            // IMPROVED: Faster scroll reveal animation with no delay
            document.addEventListener('DOMContentLoaded', () => {
                const observerOptions = {
                    threshold: 0.05,
                    rootMargin: '100px 0px -50px 0px'
                };
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);
                
                document.querySelectorAll('.scroll-reveal').forEach(el => {
                    observer.observe(el);
                });
            });
        </script>
        
        <?php
        return ob_get_clean();
    }
    
    private function render($template, $data = []) {
        extract($data);
        $viewsDir = dirname(__DIR__) . '/Views';
        include $viewsDir . "/{$template}.php";
    }
}