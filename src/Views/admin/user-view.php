<?php
// admin/user-view.php - Admin user detail view
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-gradient { background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%); }
        .nav-link { transition: all 0.3s ease; position: relative; }
        .nav-link::before { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 3px; background: linear-gradient(to bottom, #d4af37, #f4d03f); transform: scaleY(0); transition: transform 0.3s ease; }
        .nav-link:hover::before, .nav-link.active::before { transform: scaleY(1); }
        nav{ display:block; }
        footer{ display:none; }
</style>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <div class="w-72 fixed top-0 left-0 h-screen sidebar-gradient shadow-2xl flex flex-col z-20 pt-20">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-serif-elegant font-bold logo-text mb-1">Joanne's Admin</h1>
                <p class="text-sm text-gray-400">Welcome back,</p>
                <p class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            </div>
            <!-- Sidebar -->
            <?php include __DIR__ . '/partials/sidebar.php'; ?>
        </div>

        <div class="flex-1 overflow-y-auto ml-72">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">User Details</h1>
                    <a href="<?php echo rtrim(BASE_URL, '/'); ?>/admin/users" class="text-sm text-gray-600">Back to Users</a>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-500">Name</div>
                            <div class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Email</div>
                            <div class="text-lg text-gray-800"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Phone</div>
                            <div class="text-lg text-gray-800"><?php echo htmlspecialchars($user['phone'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Role</div>
                            <div class="text-lg text-gray-800"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Status</div>
                            <div class="text-lg text-gray-800"><?php echo !empty($user['is_suspended']) ? 'Suspended' : 'Active'; ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Created</div>
                            <div class="text-lg text-gray-800"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>


