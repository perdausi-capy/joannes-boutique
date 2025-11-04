<?php
// Example: Booking data is already loaded in $booking

// Compute penalty for overdue rentals
$penaltyAmount = 0;
$daysLate = 0;
$penaltyResolved = $booking['penalty_resolved'] ?? false;

if ($booking['order_type'] === 'rental' && !empty($booking['rental_end'])) {
    $rentalEnd = new DateTime($booking['rental_end']);
    $today = new DateTime();

    if ($today > $rentalEnd) {
        $daysLate = $today->diff($rentalEnd)->days;
        $penaltyPerDay = 500; // Adjust penalty per day here
        $penaltyAmount = $penaltyPerDay;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>[x-cloak]{display:none!important}</style>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');

    body { font-family: 'Inter', sans-serif; }

    .font-serif-elegant { font-family: 'Cormorant Garamond', serif; }
    .status-badge { display: inline-flex; align-items: center; padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; transition: all 0.2s ease; }
    .status-badge:hover { transform: scale(1.05); }
    .action-link { transition: all 0.2s ease; }
    .action-link:hover { transform: translateX(3px); }
</style>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-800">Booking Details</h1>
                    <a href="admin/orders" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
                    </a>
                </div>
            </header>
            <main class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Order Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Order Information</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order ID:</span>
                                <span class="font-medium">#<?php echo $booking['order_id']; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $booking['order_type'] === 'rental' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'; ?>">
                                    <?php echo ucfirst($booking['order_type']); ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Item:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($booking['item_name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-bold text-lg">₱<?php echo number_format($booking['total_amount'], 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php 
                                    echo $booking['payment_status'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                        ($booking['payment_status'] === 'verified' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'); 
                                ?>">
                                    <?php echo ucfirst($booking['payment_status']); ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span><?php echo date('M d, Y H:i', strtotime($booking['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($booking['contact_name'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span><?php echo htmlspecialchars($booking['contact_email'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span><?php echo htmlspecialchars($booking['contact_phone'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Booking Details</h2>
                        <div class="space-y-3">
                            <?php if ($booking['order_type'] === 'rental'): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rental Start:</span>
                                    <span><?php echo date('M d, Y', strtotime($booking['rental_start'])); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rental End:</span>
                                    <span><?php echo date('M d, Y', strtotime($booking['rental_end'])); ?></span>
                                </div>
                                <?php if ($booking['size']): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Size:</span>
                                    <span><?php echo htmlspecialchars($booking['size']); ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if ($daysLate > 0): ?>
                                <div class="flex justify-between mt-2">
                                    <span class="text-gray-600 font-semibold">Penalty per Day (Overdue):</span>
                                    <span class="font-bold text-red-600">₱<?php echo number_format($penaltyAmount, 2); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-semibold">Days Overdue:</span>
                                    <span class="font-medium text-red-600"><?php echo $daysLate; ?> day(s)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 font-semibold">Penalty Status:</span>
                                    <span class="font-medium <?php echo $penaltyResolved ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo $penaltyResolved ? 'Resolved' : 'Pending'; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Event Date:</span>
                                    <span><?php echo date('M d, Y', strtotime($booking['event_date'])); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Number of Guests:</span>
                                    <span><?php echo number_format($booking['quantity']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Payment Information</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span><?php echo htmlspecialchars($booking['payment_method'] ?? 'GCash'); ?></span>
                            </div>
                            <?php if ($booking['reference_number']): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Reference Number:</span>
                                <span class="font-mono"><?php echo htmlspecialchars($booking['reference_number']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if ($booking['proof_image']): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Proof of Payment:</span>
                                <a href="<?php echo BASE_URL; ?>uploads/payments/<?php echo htmlspecialchars($booking['proof_image']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-image mr-1"></i>View Image
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Actions</h2>
                    <div class="flex gap-4">
                        <?php if ($booking['payment_status'] === 'paid'): ?>
                            <form method="post" action="admin/bookings" onsubmit="return confirm('Verify this payment?')">
                                <input type="hidden" name="action" value="verify">
                                <input type="hidden" name="order_id" value="<?php echo $booking['order_id']; ?>">
                                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    <i class="fas fa-check mr-2"></i>Verify Payment
                                </button>
                            </form>
                        <?php endif; ?>
                        <?php if ($daysLate > 0 && !$penaltyResolved): ?>
                            <form method="post" action="<?= BASE_URL ?>admin/resolve-penalty" onsubmit="return confirm('Resolve the penalty for this booking?')">
                                <input type="hidden" name="order_id" value="<?= $booking['order_id'] ?>">
                                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    <i class="fas fa-dollar-sign mr-2"></i>Resolve Penalty
                                </button>
                            </form>
                        <?php endif; ?>


                        <a href="admin/orders" class="px-6 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
