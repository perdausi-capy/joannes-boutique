<?php
$pageTitle = "Your Cart | Joanne's";
?>
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-semibold mb-6">Your Shopping Cart</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <?php if (empty($cartItems)): ?>
            <p class="text-gray-600">Your cart is empty.</p>
        <?php else: ?>
            <div class="divide-y">
                <?php foreach ($cartItems as $item): ?>
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <div class="font-medium"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="text-sm text-gray-500">Qty: <?php echo (int)$item['quantity']; ?></div>
                        </div>
                        <div class="font-semibold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <div class="text-lg">Total:</div>
                <div class="text-xl font-bold text-gold-400">$<?php echo number_format($cartTotal, 2); ?></div>
            </div>
            <div class="mt-6 text-right">
                <a href="/cart/checkout" class="px-6 py-3 bg-gold-400 text-white rounded-lg hover:bg-gold-500">Checkout</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-6">
        <a href="/products" class="text-gold-400 hover:text-gold-500">Continue Shopping</a>
    </div>
    
</div>


