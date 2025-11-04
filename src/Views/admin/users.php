<?php
// admin/users.php - Admin users management page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .font-serif-elegant { font-family: 'Cormorant Garamond', serif; }
        .sidebar-gradient { background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%); }
        .nav-link { transition: all 0.3s ease; position: relative; }
        .nav-link::before { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 3px; background: linear-gradient(to bottom, #d4af37, #f4d03f); transform: scaleY(0); transition: transform 0.3s ease; }
        .nav-link:hover::before, .nav-link.active::before { transform: scaleY(1); }
        .nav-link:hover { background: rgba(212, 175, 55, 0.1); padding-left: 1.5rem; }
        .nav-link.active { background: rgba(212, 175, 55, 0.15); color: #d4af37; font-weight: 600; }
        nav{ display:block; }
        footer{ display:none; }
</style>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto ml-72">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">Manage Users</h1>
                    <div class="text-sm text-gray-600">
                        Total: <?php echo count($users ?? []); ?> users
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <?php if (!empty($users)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($users as $user): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <?php echo htmlspecialchars($user['email']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <?php echo htmlspecialchars($user['phone'] ?? ''); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo ($user['role'] === 'admin') ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                    <?php echo ucfirst($user['role']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <button onclick="toggleSuspend(<?php echo (int)$user['id']; ?>, <?php echo (int)($user['is_suspended'] ?? 0); ?>)" class="text-yellow-600 hover:text-yellow-800 text-xs">
                                                        <?php echo !empty($user['is_suspended']) ? 'Unsuspend' : 'Suspend'; ?>
                                                    </button>
                                                    <a href="admin/users/view/<?php echo (int)$user['id']; ?>" class="text-blue-600 hover:text-blue-800 text-xs">View</a>
                                                    <button onclick="deleteUser(<?php echo (int)$user['id']; ?>)" class="text-red-600 hover:text-red-800 text-xs">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-500">There are no users to display at this time.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
    <script>
        function toggleSuspend(id, isSuspended) {
            const suspend = !Boolean(isSuspended);
            if (!confirm((suspend ? 'Suspend' : 'Unsuspend') + ' this user?')) return;
            fetch('<?php echo rtrim(BASE_URL, '/'); ?>/admin/users/suspend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?php echo CSRF::generateToken(); ?>'
                },
                body: JSON.stringify({ id, suspend })
            }).then(r => r.json()).then(data => {
                if (data.success) { location.reload(); } else { alert('Failed to update user'); }
            });
        }

        function deleteUser(id) {
            if (!confirm('Delete this user? This cannot be undone.')) return;
            fetch('<?php echo rtrim(BASE_URL, '/'); ?>/admin/users/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?php echo CSRF::generateToken(); ?>'
                },
                body: JSON.stringify({ id })
            }).then(r => r.json()).then(data => {
                if (data.success) { location.reload(); } else { alert('Failed to delete user'); }
            });
        }
    </script>
</body>
</html>


