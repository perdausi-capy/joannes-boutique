<?php $pageTitle = "Customer Reviews | Joanne's"; ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-light text-gray-800 mb-6">
            What Our <span class="text-gold-400">Customers Say</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
            Read genuine reviews from our satisfied customers who have experienced our exceptional service.
        </p>
        
        <!-- Leave Review Button -->
        <button onclick="toggleReviewForm()" class="inline-block bg-gold-400 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gold-500 transition-colors">
            Leave a Review
        </button>
    </div>

    <?php if ($testimonials): ?>
        <!-- Customer Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="text-2xl text-gold-400">
                            <?php 
                            $stars = '';
                            for ($i = 0; $i < 5; $i++) {
                                $stars .= $i < $testimonial['rating'] ? '★' : '☆';
                            }
                            echo $stars;
                            ?>
                        </div>
                        <span class="ml-2 text-sm text-gray-500"><?php echo $testimonial['rating'] ?>/5</span>
                    </div>
                    
                    <blockquote class="text-gray-700 mb-4 italic">
                        "<?php echo nl2br(htmlspecialchars($testimonial['message'])); ?>"
                    </blockquote>
                    
                    <div class="border-t pt-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gold-400 rounded-full flex items-center justify-center text-white font-semibold">
                                <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <p class="text-sm text-gray-500">Verified Customer</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            <?php echo date('F j, Y', strtotime($testimonial['created_at'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="text-6xl text-gold-400 mb-4">⭐</div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">No Reviews Yet</h2>
            <p class="text-gray-600 mb-6">Be the first to share your experience with us!</p>
            <button onclick="toggleReviewForm()" class="inline-block bg-gold-400 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gold-500 transition-colors">
                Leave the First Review
            </button>
        </div>
    <?php endif; ?>

    <!-- Review Form Modal -->
    <div id="reviewForm" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">Leave a Review</h3>
                    <button onclick="toggleReviewForm()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <?php if (isset($_SESSION['testimonial_message'])): ?>
                    <script>window.__testimonialSubmitted = true;</script>
                    <div class="mb-4 p-4 rounded-lg <?php echo $_SESSION['testimonial_message']['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo htmlspecialchars($_SESSION['testimonial_message']['text']); ?>
                    </div>
                    <?php unset($_SESSION['testimonial_message']); ?>
                <?php endif; ?>

                <form method="post" action="<?php echo rtrim(BASE_URL, '/'); ?>/testimonials/submit">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating <span class="text-red-500">*</span></label>
                            <div class="flex space-x-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="<?php echo $i; ?>" class="sr-only">
                                        <span class="text-2xl">☆</span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Review <span class="text-red-500">*</span></label>
                            <textarea id="message" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-gold-400 focus:border-transparent" name="message" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-gold-400 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gold-500 transition-colors">
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleReviewForm() {
            const modal = document.getElementById('reviewForm');
            modal.classList.toggle('hidden');
        }

        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('input[name="rating"]');
            const starLabels = document.querySelectorAll('input[name="rating"] + span');
            
            stars.forEach((star, index) => {
                star.addEventListener('change', function() {
                    starLabels.forEach((label, labelIndex) => {
                        if (labelIndex < index + 1) {
                            label.textContent = '★';
                        } else {
                            label.textContent = '☆';
                        }
                    });
                });
            });

            // Auto-open the modal if there is a session message (after submit)
            if (window.__testimonialSubmitted) {
                const modal = document.getElementById('reviewForm');
                if (modal && modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                }
            }
        });
    </script>
</div>
