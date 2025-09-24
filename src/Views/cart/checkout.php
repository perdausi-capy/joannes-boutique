<?php
$pageTitle = "Checkout | Joanne's";
?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-semibold mb-6">Checkout</h1>
    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="/cart/checkout" class="bg-white p-6 rounded-lg shadow space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700">Shipping Address</label>
            <textarea name="shipping_address" class="mt-1 w-full border rounded px-3 py-2" required><?php echo htmlspecialchars($orderData['shipping_address'] ?? ''); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Billing Address</label>
            <textarea name="billing_address" class="mt-1 w-full border rounded px-3 py-2"><?php echo htmlspecialchars($orderData['billing_address'] ?? ''); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
            <select name="payment_method" class="mt-1 w-full border rounded px-3 py-2">
                <option value="bank_transfer" <?php echo (($orderData['payment_method'] ?? '') === 'bank_transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                <option value="cod" <?php echo (($orderData['payment_method'] ?? '') === 'cod') ? 'selected' : ''; ?>>Cash on Delivery</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" class="mt-1 w-full border rounded px-3 py-2"><?php echo htmlspecialchars($orderData['notes'] ?? ''); ?></textarea>
        </div>
        <div class="flex justify-between items-center">
            <div class="text-lg">Total:</div>
            <div class="text-xl font-bold text-gold-400">$<?php echo number_format($cartTotal ?? 0, 2); ?></div>
        </div>
        <div class="text-right">
            <button type="submit" class="px-6 py-3 bg-gold-400 text-white rounded-lg hover:bg-gold-500">Place Order</button>
        </div>
    </form>
</div>


