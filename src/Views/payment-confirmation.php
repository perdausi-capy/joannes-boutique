<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - Joanne's Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?>" class="text-xl font-bold text-gold-400">Joanne's Boutique</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo BASE_URL; ?>" class="text-gray-700 hover:text-gold-400">Home</a>
                    <a href="<?php echo BASE_URL; ?>products" class="text-gray-700 hover:text-gold-400">Products</a>
                    <a href="<?php echo BASE_URL; ?>packages" class="text-gray-700 hover:text-gold-400">Packages</a>
                    <a href="<?php echo BASE_URL; ?>contact" class="text-gray-700 hover:text-gold-400">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Success Header -->
            <div class="bg-green-500 text-white px-6 py-8 text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-6xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">Payment Confirmed!</h1>
                <p class="text-green-100">Thank you for your payment. We'll process your order shortly.</p>
            </div>

            <div class="p-6">
                <!-- Order Details -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-semibold">#<?php echo $order['order_id']; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Paid
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Method</p>
                                <p class="font-semibold">GCash</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Reference Number</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($order['reference_number']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Summary -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Item Summary</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-4">
                            <?php if ($order['item_image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($order['item_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($order['item_name']); ?>" 
                                     class="w-20 h-20 object-cover rounded">
                            <?php endif; ?>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($order['item_name']); ?></h3>
                                <p class="text-gray-600">
                                    <?php if ($order['order_type'] === 'rental'): ?>
                                        Rental Period: <?php echo date('M d, Y', strtotime($order['rental_start'])); ?> - <?php echo date('M d, Y', strtotime($order['rental_end'])); ?>
                                        <?php if ($order['size']): ?>
                                            <br>Size: <?php echo htmlspecialchars($order['size']); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Event Date: <?php echo date('M d, Y', strtotime($order['event_date'])); ?>
                                        <br>Guests: <?php echo number_format($order['quantity']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gold-400">₱<?php echo number_format($order['total_amount'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <?php if ($order['contact_name'] || $order['contact_email'] || $order['contact_phone']): ?>
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Contact Information</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid md:grid-cols-3 gap-4">
                            <?php if ($order['contact_name']): ?>
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($order['contact_name']); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if ($order['contact_email']): ?>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($order['contact_email']); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if ($order['contact_phone']): ?>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($order['contact_phone']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Next Steps -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">What's Next?</h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-blue-500 mt-1"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-blue-800">Order Processing</p>
                                    <p class="text-blue-700">We'll review your payment and contact you within 24 hours to confirm your order.</p>
                                </div>
                            </div>
                            <?php if ($order['order_type'] === 'rental'): ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar text-blue-500 mt-1"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-blue-800">Rental Pickup</p>
                                    <p class="text-blue-700">We'll arrange pickup details for your rental period.</p>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar text-blue-500 mt-1"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-blue-800">Event Planning</p>
                                    <p class="text-blue-700">Our team will contact you to discuss event details and coordination.</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="<?php echo BASE_URL; ?>" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-home mr-2"></i>Back to Home
                    </a>
                    <div class="space-x-4">
                        <a href="<?php echo BASE_URL; ?>products" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">
                            Browse More Products
                        </a>
                        <a href="<?php echo BASE_URL; ?>packages" class="bg-gold-400 text-white px-4 py-2 rounded-md hover:bg-gold-500">
                            View Packages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Joanne's Boutique. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
