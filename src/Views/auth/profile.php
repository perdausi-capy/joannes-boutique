<?php
$pageTitle = "Profile | Joanne's";
?>
<div class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-2xl font-semibold mb-6">Profile</h1>

    <?php if (!empty($success)): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="/auth/profile" class="bg-white p-6 rounded-lg shadow space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" name="first_name" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name" class="mt-1 w-full border rounded px-3 py-2" required value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
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

    <div class="mt-4 text-center">
        <a href="/auth/logout" class="text-gold-400 hover:text-gold-500">Logout</a>
    </div>
</div>
