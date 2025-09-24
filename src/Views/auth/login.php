<?php
$pageTitle = "Login | Joanne's";
?>
<div class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-2xl font-semibold mb-6">Login</h1>

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="/auth/login" class="bg-white p-6 rounded-lg shadow space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="w-full px-4 py-2 bg-gold-400 text-white rounded hover:bg-gold-500">Sign in</button>
    </form>

    <div class="mt-4 text-center">
        <a href="/" class="text-gold-400 hover:text-gold-500">Back to Home</a>
    </div>
</div>


