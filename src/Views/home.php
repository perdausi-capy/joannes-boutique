<!DOCTYPE html>
<html lang="en" x-data="{ 
    mobileMenuOpen: false, 
    cartOpen: false, 
    cartCount: 0,
    cartItems: [],
    searchOpen: false,
    loading: false
}" x-init="loadCartCount()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Joanne\'s Gown and Suits - Elegance for Every Occasion'; ?></title>
    <base href="<?php echo BASE_URL; ?>">
    <meta name="description" content="<?php echo $pageDescription ?? 'Luxury gowns and suits for special occasions. Custom fittings, elegant designs, and exceptional craftsmanship.'; ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            400: '#d4af37',
                            500: '#b8941f',
                            600: '#9c7a1a'
                        }
                    },
                    fontFamily: {
                        serif: ['Georgia', 'serif'],
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .hero-gradient {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(184, 148, 31, 0.2) 100%);
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .slide-in-right {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .slide-in-right.active {
            transform: translateX(0);
        }
    </style>
    
</head>
<body class="font-serif text-gray-800 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect shadow-lg transition-all duration-300" 
         :class="{ 'bg-white/98': window.scrollY > 50 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?php echo rtrim(BASE_URL, '/'); ?>/" class="text-2xl md:text-3xl font-bold text-gold-400 hover:text-gold-500 transition-colors">
                        Joanne's
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="flex items-center space-x-8">
                        <a href="/" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Home
                        </a>
                        <a href="/products" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Gallery
                        </a>
                        <a href="/packages" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Packages
                        </a>
                        <a href="/testimonials" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Reviews
                        </a>
                        <a href="/contact" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Contact Us
                        </a>
                    </div>
                </div>

                <!-- Right side buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Search Button -->
                    <button @click="searchOpen = true" 
                            class="text-gray-700 hover:text-gold-400 p-2 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Cart Button -->
                    <button @click="cartOpen = true" 
                            class="relative text-gray-700 hover:text-gold-400 p-2 transition-colors">
                        <i class="fas fa-shopping-bag"></i>
                        <span x-show="cartCount > 0" 
                              x-text="cartCount"
                              class="absolute -top-1 -right-1 bg-gold-400 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        </span>
                    </button>

                    <!-- Auth Buttons -->
                    <?php if (Auth::isLoggedIn()): ?>
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    class="flex items-center space-x-2 text-gray-700 hover:text-gold-400 transition-colors">
                                <span>Hello, <?php echo htmlspecialchars(Auth::user()['first_name']); ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <div x-show="userMenuOpen" 
                                 x-cloak
                                 @click.away="userMenuOpen = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <a href="auth/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="auth/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                <?php if (Auth::isAdmin()): ?>
                                    <a href="admin/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>
                                <?php endif; ?>
                                <hr class="my-1">
                                <a href="auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="auth/login" 
                           class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Login
                        </a>
                        <a href="auth/register" 
                           class="bg-gold-400 hover:bg-gold-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Register
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="text-gray-700 hover:text-gold-400 p-2">
                        <i class="fas fa-bars" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="md:hidden bg-white border-t shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Home</a>
                <a href="/products" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Gallery</a>
                <a href="/packages" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Packages</a>
                <a href="/testimonials" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Reviews</a>
                <a href="/contact" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Contact Us</a>
                
                <hr class="my-2">
                
                <button @click="searchOpen = true; mobileMenuOpen = false" 
                        class="w-full text-left px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                
                <button @click="cartOpen = true; mobileMenuOpen = false" 
                        class="w-full text-left px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>Cart
                    <span x-show="cartCount > 0" x-text="'(' + cartCount + ')'" class="text-gold-400"></span>
                </button>
                
                <?php if (Auth::isLoggedIn()): ?>
                    <div class="border-t pt-2 mt-2">
                        <div class="px-3 py-2 text-sm text-gray-500">
                            Hello, <?php echo htmlspecialchars(Auth::user()['first_name']); ?>
                        </div>
                        <a href="/auth/profile" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Profile</a>
                        <a href="/auth/orders" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">My Orders</a>
                        <?php if (Auth::isAdmin()): ?>
                            <a href="/admin/dashboard" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Admin Panel</a>
                        <?php endif; ?>
                        <a href="/auth/logout" class="block px-3 py-2 text-red-600 hover:bg-gray-50 rounded-md transition-colors">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="border-t pt-2 mt-2">
                        <a href="/auth/login" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Login</a>
                        <a href="/auth/register" class="block px-3 py-2 bg-gold-400 text-white hover:bg-gold-500 rounded-md transition-colors mx-3">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div x-show="searchOpen" 
         x-cloak
         @keydown.escape="searchOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-start justify-center min-h-screen pt-20 px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="searchOpen = false"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6"
                 x-data="{ searchTerm: '', searchResults: [], searching: false }">
                <h3 class="text-lg font-semibold mb-4">Search Products</h3>
                
                <div class="relative">
                    <input type="text" 
                           x-model="searchTerm"
                           @input.debounce.300ms="searchProducts()"
                           placeholder="Search gowns, suits, accessories..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
                
                <div x-show="searching" class="mt-4 text-center">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-gold-400"></div>
                </div>
                
                <div x-show="searchResults.length > 0" class="mt-4 max-h-60 overflow-y-auto">
                    <template x-for="result in searchResults">
                        <a :href="'products/show/' + result.id" 
                           class="block p-3 hover:bg-gray-50 border-b last:border-b-0"
                           @click="searchOpen = false">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span x-text="result.category_name.charAt(0)" class="text-gold-400 font-semibold"></span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium" x-text="result.name"></h4>
                                    <p class="text-sm text-gray-500" x-text="' + result.price"></p>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
                
                <button @click="searchOpen = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Shopping Cart Sidebar -->
    <div x-show="cartOpen" 
         x-cloak
         @keydown.escape="cartOpen = false"
         class="fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="cartOpen = false"></div>
        
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl"
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="text-lg font-semibold">Shopping Cart</h2>
                    <button @click="cartOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4">
                    <div x-show="cartItems.length === 0" class="text-center text-gray-500 mt-8">
                        <i class="fas fa-shopping-bag text-4xl mb-4"></i>
                        <p>Your cart is empty</p>
                        <button @click="cartOpen = false" class="mt-4 text-gold-400 hover:text-gold-500">Continue Shopping</button>
                    </div>
                    
                    <div x-show="cartItems.length > 0">
                        <template x-for="item in cartItems" :key="item.id">
                            <div class="flex items-center space-x-4 py-4 border-b">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gold-400 text-2xl">👗</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium" x-text="item.name"></h3>
                                    <p class="text-sm text-gray-500" x-text="' + item.price"></p>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                                class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-xs hover:bg-gray-50">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span x-text="item.quantity" class="px-2"></span>
                                        <button @click="updateQuantity(item.id, item.quantity + 1)" 
                                                class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-xs hover:bg-gray-50">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <button @click="removeFromCart(item.id)" 
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div x-show="cartItems.length > 0" class="border-t p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold">Total:</span>
                        <span class="font-bold text-xl text-gold-400" x-text="' + cartTotal"></span>
                    </div>
                    <div class="space-y-2">
                        <a href="cart" @click="cartOpen = false" 
                           class="block w-full text-center py-2 border border-gold-400 text-gold-400 rounded-lg hover:bg-gold-50 transition-colors">
                            View Cart
                        </a>
                        <a href="cart/checkout" @click="cartOpen = false" 
                           class="block w-full text-center py-2 bg-gold-400 text-white rounded-lg hover:bg-gold-500 transition-colors">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gold-400 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-300 hover:text-white transition-colors">Home</a></li>
                        <li><a href="/products" class="text-gray-300 hover:text-white transition-colors">Gallery</a></li>
                        <li><a href="/packages" class="text-gray-300 hover:text-white transition-colors">Packages</a></li>
                        <li><a href="/testimonials" class="text-gray-300 hover:text-white transition-colors">Reviews</a></li>
                        <li><a href="/contact" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gold-400 mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Custom Gowns</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Tailored Suits</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Alterations</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Wedding Collections</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Accessories</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gold-400 mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li>123 Fashion Avenue</li>
                        <li>Makati City, Metro Manila 1200</li>
                        <li>Phone: +63 2 8123 4567</li>
                        <li>Email: info@joannesgowns.com</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gold-400 mb-4">Follow Us</h3>
                    <div class="flex space-x-4 mb-4">
                        <a href="#" class="w-10 h-10 bg-gold-400 rounded-full flex items-center justify-center hover:bg-gold-500 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gold-400 rounded-full flex items-center justify-center hover:bg-gold-500 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gold-400 rounded-full flex items-center justify-center hover:bg-gold-500 transition-colors">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                    <p class="text-gray-400 text-sm">Stay updated with our latest collections and special offers.</p>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Joanne's Gown and Suits. All rights reserved. | 
                   <a href="#" class="hover:text-white">Privacy Policy</a> | 
                   <a href="#" class="hover:text-white">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript Functions -->
    <script>
        // Global Alpine.js functions
        function loadCartCount() {
            fetch('/api/cart-count')
                .then(response => response.json())
                .then(data => {
                    this.cartCount = data.count || 0;
                })
                .catch(error => console.error('Error loading cart count:', error));
        }

        function loadCartItems() {
            fetch('/cart/api/items')
                .then(response => response.json())
                .then(data => {
                    this.cartItems = data.items || [];
                    this.cartTotal = data.total || 0;
                    this.cartCount = data.count || 0;
                })
                .catch(error => console.error('Error loading cart items:', error));
        }

        function addToCart(productId, quantity = 1, goToCart = false) {
            this.loading = true;
            
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            formData.append('csrf_token', '<?php echo CSRF::generateToken(); ?>');

            fetch('/cart/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.cartCount = data.cart_count;
                    if (goToCart) {
                        window.location = '/cart';
                        return;
                    }
                    showNotification('Product added to cart!', 'success');
                } else {
                    showNotification(data.message || 'Error adding to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding to cart', 'error');
            })
            .finally(() => {
                this.loading = false;
            });
        }

        function updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) {
                removeFromCart(itemId);
                return;
            }

            const formData = new FormData();
            formData.append('item_id', itemId);
            formData.append('quantity', newQuantity);
            formData.append('csrf_token', '<?php echo CSRF::generateToken(); ?>');

            fetch('/cart/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCartItems();
                }
            })
            .catch(error => console.error('Error updating cart:', error));
        }

        function removeFromCart(itemId) {
            const formData = new FormData();
            formData.append('item_id', itemId);
            formData.append('csrf_token', '<?php echo CSRF::generateToken(); ?>');

            fetch('/cart/remove', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCartItems();
                    showNotification('Item removed from cart', 'info');
                }
            })
            .catch(error => console.error('Error removing from cart:', error));
        }

        function searchProducts() {
            if (this.searchTerm.length < 2) {
                this.searchResults = [];
                return;
            }

            this.searching = true;
            
            fetch(`/api/search?q=${encodeURIComponent(this.searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    this.searchResults = data.results || [];
                })
                .catch(error => console.error('Search error:', error))
                .finally(() => {
                    this.searching = false;
                });
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-white'
            };
            
            notification.className += ' ' + colors[type];
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Initialize cart on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCartCount();
        });
    </script>
</body>
</html>


