<!DOCTYPE html>
<html lang="en" x-data="{ 
    mobileMenuOpen: false, 
    searchOpen: false,
    loading: false
}">
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
                        <a href="<?php echo rtrim(BASE_URL, '/'); ?>" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Home
                        </a>
                        <a href="products" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Gallery
                        </a>
                        <a href="packages" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Packages
                        </a>
                        <a href="testimonials" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Reviews
                        </a>
                        <a href="contact" class="text-gray-700 hover:text-gold-400 px-3 py-2 text-sm font-medium transition-colors">
                            Contact Us
                        </a>
                    </div>
                </div>

                <!-- Right side buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Search Button -->
                    <!-- <button @click="searchOpen = true" 
                            class="text-gray-700 hover:text-gold-400 p-2 transition-colors">
                        <i class="fas fa-search"></i>
                    </button> -->


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
                                
                                <!-- Show "My Orders" only for customers, not admins -->
                                <?php if (!Auth::isAdmin()): ?>
                                    <a href="auth/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                <?php endif; ?>
                                
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
                <a href="products" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Gallery</a>
                <a href="packages" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Packages</a>
                <a href="testimonials" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Reviews</a>
                <a href="contact" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Contact Us</a>
                
                <hr class="my-2">
                
                <button @click="searchOpen = true; mobileMenuOpen = false" 
                        class="w-full text-left px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                
                <?php if (Auth::isLoggedIn()): ?>
                    <div class="border-t pt-2 mt-2">
                        <div class="px-3 py-2 text-sm text-gray-500">
                            Hello, <?php echo htmlspecialchars(Auth::user()['first_name']); ?>
                        </div>
                        <a href="auth/profile" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Profile</a>
                        
                        <!-- Show "My Orders" only for customers, not admins -->
                        <?php if (!Auth::isAdmin()): ?>
                            <a href="auth/orders" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">My Orders</a>
                        <?php endif; ?>
                        
                        <?php if (Auth::isAdmin()): ?>
                            <a href="admin/dashboard" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Admin Panel</a>
                        <?php endif; ?>
                        <a href="auth/logout" class="block px-3 py-2 text-red-600 hover:bg-gray-50 rounded-md transition-colors">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="border-t pt-2 mt-2">
                        <a href="auth/login" class="block px-3 py-2 text-gray-700 hover:text-gold-400 hover:bg-gray-50 rounded-md transition-colors">Login</a>
                        <a href="auth/register" class="block px-3 py-2 bg-gold-400 text-white hover:bg-gold-500 rounded-md transition-colors mx-3">Register</a>
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
                 x-data="{ 
                    searchTerm: '', 
                    searchResults: [], 
                    searching: false,
                    searchProducts() {
                        if (this.searchTerm.length < 2) {
                            this.searchResults = [];
                            return;
                        }
                        this.searching = true;
                        fetch('<?php echo rtrim(BASE_URL, '/'); ?>/api/search?q=' + encodeURIComponent(this.searchTerm))
                            .then(response => response.json())
                            .then(data => { this.searchResults = data.results || []; })
                            .catch(() => { this.searchResults = []; })
                            .finally(() => { this.searching = false; });
                    }
                 }">
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
                                    <p class="text-sm text-gray-500" x-text="result.price"></p>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>

                <div x-show="!searching && searchTerm.length >= 2 && searchResults.length === 0" class="mt-4 text-center text-gray-500">
                    No results found.
                </div>
                
                <button @click="searchOpen = false" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
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
                        <li><a href="products" class="text-gray-300 hover:text-white transition-colors">Gallery</a></li>
                        <li><a href="packages" class="text-gray-300 hover:text-white transition-colors">Packages</a></li>
                        <li><a href="testimonials" class="text-gray-300 hover:text-white transition-colors">Reviews</a></li>
                        <li><a href="contact" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
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
                        <li>Pilar Street drive thru</li>
                        <li>Zamboanga City, Zamboanga del sur 7000</li>
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
        function searchProducts() {
            if (this.searchTerm.length < 2) {
                this.searchResults = [];
                return;
            }

            this.searching = true;
            
            fetch(`api/search?q=${encodeURIComponent(this.searchTerm)}`)
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
    </script>

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
                    
                    <form id="rentalForm" method="post" action="rental/create">
                        <input type="hidden" name="product_id" id="rentalProductId">
                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="font-medium" id="rentalItemName"></div>
                                <div class="text-sm text-gray-600">₱<span id="rentalItemPrice"></span></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rental Start Date</label>
                            <input type="date" name="rental_start" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rental End Date</label>
                            <input type="date" name="rental_end" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Size (Optional)</label>
                            <input type="text" name="size" placeholder="e.g., Small, Medium, Large" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Name</label>
                            <input type="text" name="contact_name" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                            <input type="email" name="contact_email" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="tel" name="contact_phone" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="closeRentalModal()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRentalModal(productId, productName, productPrice) {
            console.log('Opening rental modal:', { productId, productName, productPrice });
            
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
            
            console.log('Modal should be visible now');
        }

        function closeRentalModal() {
            const modal = document.getElementById('rentalModal');
            if (modal) {
                modal.classList.add('hidden');
            }
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
        });
    </script>
</body>
</html>