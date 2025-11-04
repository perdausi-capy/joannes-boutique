<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Joanne's Boutique</title>
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
            <!-- Header -->
            <div class="bg-gold-400 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">Complete Your Payment</h1>
                <p class="text-gold-100">Order #<?php echo $order['order_id']; ?></p>
            </div>

            <div class="p-6">
                <!-- Order Summary -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
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

                <!-- Payment Instructions -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Payment Instructions</h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <i class="fas fa-mobile-alt text-blue-500 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-blue-800">Pay via GCash</h3>
                                <p class="text-blue-700 mb-2">Send payment to:</p>
                                <div class="bg-white rounded p-3 border">
                                    <p class="font-mono text-lg font-bold">0917 2664 832</p>
                                    <p class="text-sm text-gray-600">Joanne's Boutique</p>
                                </div>
                                <p class="text-blue-700 mt-2">
                                    <strong>Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PayMongo GCash Payment Component -->
                <div x-data="{
                    loading: false,
                    error: '',
                    bookingId: '<?php echo $order['order_id']; ?>',
                    amount: '<?php echo $order['total_amount']; ?>',
                    customerName: '<?php echo addslashes($order['contact_name'] ?? ''); ?>',
                    customerEmail: '<?php echo addslashes($order['contact_email'] ?? ''); ?>',
                    payWithGCash() {
                        this.loading = true;
                        this.error = '';
                        fetch('../api/create_payment.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                booking_id: this.bookingId,
                                amount: this.amount,
                                customer_name: this.customerName,
                                customer_email: this.customerEmail
                            })
                        })
                        .then(r => r.json())
                        .then(resp => {
                            this.loading = false;
                            if (resp.success && resp.checkout_url) {
                                window.location = resp.checkout_url;
                            } else {
                                this.error = resp.message || 'Failed to create payment link';
                            }
                        }).catch(() => { this.loading = false; this.error = 'Server error. Try again.'; });
                    }
                }" class="text-center">
                    <button @click="payWithGCash" :disabled="loading" type="button" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold text-lg mt-4 shadow hover:bg-blue-700 disabled:opacity-75 disabled:cursor-not-allowed flex items-center justify-center mx-auto">
                        <template x-if="loading">
                            <svg class="w-5 h-5 text-white animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                        </template>
                        <span>Pay with GCash (via PayMongo)</span>
                    </button>
                    <div x-text="error" class="text-red-600 mt-2"></div>
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
