<?php $pageTitle = "Contact Us | Joanne's"; ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-light text-gray-800 mb-6">
            Get in <span class="text-gold-400">Touch</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            We'd love to hear from you. Send us a message and we'll respond as soon as possible.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Send us a Message</h2>
            
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $message['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                    <?php echo htmlspecialchars($message['text']); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/contact">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="6" required 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gold-400 focus:border-transparent"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-gold-400 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gold-500 transition-colors">
                        Send Message
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="space-y-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Contact Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-gold-100 p-3 rounded-full mr-4">
                            <i class="fas fa-map-marker-alt text-gold-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Address</h4>
                            <p class="text-gray-600">123 Fashion Street<br>City, State 12345</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="bg-gold-100 p-3 rounded-full mr-4">
                            <i class="fas fa-phone text-gold-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Phone</h4>
                            <p class="text-gray-600">(555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="bg-gold-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-gold-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Email</h4>
                            <p class="text-gray-600">info@joannes.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="bg-gold-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-gold-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Business Hours</h4>
                            <p class="text-gray-600">Mon-Fri: 9AM-6PM<br>Sat: 10AM-4PM<br>Sun: Closed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-gold-400 to-yellow-400 rounded-xl shadow-lg p-8 text-white">
                <h3 class="text-xl font-semibold mb-4">Need a Consultation?</h3>
                <p class="mb-6">Schedule a personal fitting or discuss your custom design needs.</p>
                <a href="/booking" class="inline-block bg-white text-gold-400 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Book Consultation
                </a>
            </div>
        </div>
    </div>
</div>
