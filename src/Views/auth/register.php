<?php
$pageTitle = "Register | Joanne's";
?>
<div class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-2xl font-semibold mb-6">Register</h1>

    <?php if (!empty(
        $errors)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <?php foreach ($errors as $error): ?>
                <div><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/auth/register" class="bg-white p-6 rounded-lg shadow space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" name="first_name" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" class="mt-1 w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="confirm_password" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="w-full px-4 py-2 bg-gold-400 text-white rounded hover:bg-gold-500">Register</button>
    </form>

    <div class="mt-4 text-center">
        <a href="/auth/login" class="text-gold-400 hover:text-gold-500">Already have an account? Sign in</a>
    </div>
</div>
