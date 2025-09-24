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
        $testimonials = $this->testimonialModel->getApproved(3);
        
        $content = $this->renderHome($featuredProducts, $testimonials);
        $this->render('layout', [
            'content' => $content,
            'pageTitle' => 'Joanne\'s Gown and Suits - Elegance for Every Occasion'
        ]);
    }
    
    private function renderHome($featuredProducts, $testimonials) {
        ob_start();
        ?>
        <!-- Hero Section -->
        <section class="relative h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 hero-gradient">
            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
                <h1 class="text-4xl md:text-6xl font-light text-gray-800 mb-6 leading-tight">
                    Elegance for Every <span class="text-gold-400">Occasion</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Discover exquisite gowns and sophisticated suits crafted for life's most important moments
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/products" 
                       class="inline-block px-8 py-4 bg-gold-400 text-white rounded-lg font-semibold hover:bg-gold-500 transform hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        Browse Collection
                    </a>
                    <a href="/booking" 
                       class="inline-block px-8 py-4 border-2 border-gold-400 text-gold-400 rounded-lg font-semibold hover:bg-gold-400 hover:text-white transition-all duration-300">
                        Book Consultation
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-light text-gray-800 mb-4">Featured Collections</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Handpicked selections showcasing our finest craftsmanship and design excellence</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($featuredProducts as $product): ?>
                    <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                        <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <?php if ($product['image']): ?>
                                <img src="/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="w-full h-64 object-cover">
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
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-gold-400">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </span>
                                <div class="flex space-x-2">
                                    <a href="/products/show/<?php echo $product['id']; ?>" 
                                       class="px-4 py-2 border border-gold-400 text-gold-400 rounded-lg hover:bg-gold-400 hover:text-white transition-colors">
                                        View Details
                                    </a>
                                    <button onclick="addToCart(<?php echo $product['id']; ?>)" 
                                            class="px-4 py-2 bg-gold-400 text-white rounded-lg hover:bg-gold-500 transition-colors">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-12">
                    <a href="/products" 
                       class="inline-block px-8 py-3 border-2 border-gold-400 text-gold-400 rounded-lg font-semibold hover:bg-gold-400 hover:text-white transition-colors">
                        View All Products
                    </a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-light text-gray-800 mb-6">Our Story</h2>
                        <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                            For over two decades, Joanne's has been synonymous with exceptional craftsmanship and timeless elegance. 
                            Founded on the belief that every individual deserves to feel extraordinary, we specialize in creating 
                            bespoke gowns and suits that celebrate life's most precious moments.
                        </p>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Our master tailors combine traditional techniques with contemporary designs, ensuring each piece is not 
                            just clothing, but a work of art that reflects your unique style and personality.
                        </p>
                        
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gold-400 mb-2">20+</div>
                                <div class="text-gray-600">Years Experience</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-gold-400 mb-2">5000+</div>
                                <div class="text-gray-600">Happy Clients</div>
                            </div>
                        </div>
                        
                        <a href="/booking" 
                           class="inline-block px-6 py-3 bg-gold-400 text-white rounded-lg font-semibold hover:bg-gold-500 transition-colors">
                            Book Consultation
                        </a>
                    </div>
                    <div class="lg:order-first">
                        <div class="relative">
                            <div class="aspect-w-4 aspect-h-5 bg-gradient-to-br from-gold-200 to-gold-400 rounded-2xl shadow-2xl">
                                <div class="flex items-center justify-center text-8xl text-white opacity-20">👗</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <?php if (!empty($testimonials)): ?>
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-light text-gray-800 mb-4">What Our Clients Say</h2>
                    <p class="text-gray-600">Real experiences from our valued customers</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="bg-gray-50 p-6 rounded-xl relative">
                        <div class="absolute top-4 left-4 text-4xl text-gold-400 opacity-20">"</div>
                        <div class="flex items-center mb-4">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'text-gold-400' : 'text-gray-300'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-600 mb-4 italic">
                            "<?php echo htmlspecialchars($testimonial['message']); ?>"
                        </p>
                        <div class="font-semibold text-gold-400">
                            - <?php echo htmlspecialchars($testimonial['name']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- CTA Section -->
        <section class="py-16 bg-gold-400">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl md:text-4xl font-light text-white mb-4">Ready to Begin Your Journey?</h2>
                <p class="text-xl text-gold-100 mb-8">
                    Schedule a consultation and let us create something extraordinary for you
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
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    private function render($template, $data = []) {
        extract($data);
        $viewsDir = dirname(__DIR__) . '/Views';
        include $viewsDir . "/{$template}.php";
    }
}


