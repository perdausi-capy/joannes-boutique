<?php
$pageTitle = "Profile | Joanne's";
$initials = strtoupper(substr($user['first_name'] ?? '', 0, 1) . substr($user['last_name'] ?? '', 0, 1));
?>
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Profile Card -->
        <div class="md:w-1/3 bg-white rounded-lg shadow p-6 flex flex-col items-center">
            <div class="w-24 h-24 rounded-full bg-gold-400 flex items-center justify-center text-3xl text-white font-bold mb-4">
                <?php echo $initials; ?>
            </div>
            <div class="text-center">
                <div class="text-xl font-semibold text-gray-800 mb-1">
                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                </div>
                <div class="text-gray-500 text-sm mb-2">
                    <?php echo htmlspecialchars($user['email']); ?>
                </div>
                <div class="text-gray-400 text-xs mb-2">
                    Member since <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?>
                </div>
                <div class="text-gray-500 text-sm">
                    <?php echo htmlspecialchars($user['phone'] ?? ''); ?>
                </div>
            </div>
            <a href="/auth/logout" class="mt-6 inline-block text-gold-400 hover:text-gold-500">Logout</a>
        </div>

        <!-- Profile Forms & Activity -->
        <div class="md:w-2/3 space-y-8">
            <!-- Profile Update Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Update Profile</h2>
                <?php if (!empty($success)): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"> <?php echo htmlspecialchars($success); ?> </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"> <?php echo htmlspecialchars($error); ?> </div>
                <?php endif; ?>
                <form method="post" action="/auth/profile" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" class="mt-1 w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" class="mt-1 w-full border rounded px-3 py-2 bg-gray-100" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-gold-400 text-white rounded hover:bg-gold-500">Update Profile</button>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Change Password</h2>
                <?php if (!empty($pw_success)): ?>
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"> <?php echo htmlspecialchars($pw_success); ?> </div>
                <?php endif; ?>
                <?php if (!empty($pw_error)): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"> <?php echo htmlspecialchars($pw_error); ?> </div>
                <?php endif; ?>
                <form method="post" action="/auth/change-password" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" class="mt-1 w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" class="mt-1 w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="mt-1 w-full border rounded px-3 py-2" required>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-gold-400 text-white rounded hover:bg-gold-500">Change Password</button>
                </form>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Orders -->
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Recent Orders</h3>
                        <?php if (!empty($orders)): ?>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                                    <li class="border-b pb-2">
                                        <div class="font-medium text-gold-400">#<?php echo htmlspecialchars($order['order_number']); ?></div>
                                        <div><?php echo date('M d, Y', strtotime($order['created_at'])); ?> - $<?php echo number_format($order['total_amount'], 2); ?></div>
                                        <div class="text-xs text-gray-400">Status: <?php echo ucfirst($order['status']); ?></div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-gray-400 text-xs">No orders yet.</div>
                        <?php endif; ?>
                    </div>
                    <!-- Bookings -->
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Recent Bookings</h3>
                        <?php if (!empty($bookings)): ?>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <?php foreach (array_slice($bookings, 0, 3) as $booking): ?>
                                    <li class="border-b pb-2">
                                        <div class="font-medium text-gold-400"><?php echo htmlspecialchars($booking['service_type']); ?></div>
                                        <div><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></div>
                                        <div class="text-xs text-gray-400">Status: <?php echo ucfirst($booking['status']); ?></div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-gray-400 text-xs">No bookings yet.</div>
                        <?php endif; ?>
                    </div>
                    <!-- Testimonials -->
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Your Testimonials</h3>
                        <?php if (!empty($testimonials)): ?>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <?php foreach (array_slice($testimonials, 0, 3) as $testimonial): ?>
                                    <li class="border-b pb-2">
                                        <div class="font-medium text-gold-400">Rating: <?php echo $testimonial['rating']; ?>/5</div>
                                        <div class="italic">"<?php echo htmlspecialchars(mb_strimwidth($testimonial['message'], 0, 40, '...')); ?>"</div>
                                        <div class="text-xs text-gray-400">Status: <?php echo ucfirst($testimonial['status']); ?></div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-gray-400 text-xs">No testimonials yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
