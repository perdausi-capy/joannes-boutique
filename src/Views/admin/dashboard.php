<?php
// src/Views/admin/dashboard.php
// This is the admin dashboard view, adapted from the provided code.

// Get dashboard statistics
$productModel = new Product();
$orderModel = new Order();
$userModel = new User();
$testimonialModel = new Testimonial();

$stats = [
    'total_products' => $productModel->count(),
    'total_orders' => $orderModel->count(),
    'total_customers' => $userModel->count(['role' => 'customer']),
    'pending_testimonials' => $testimonialModel->count(['status' => 'pending'])
];

// Get recent orders
$recentOrders = $orderModel->findAll(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Joanne's</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            </div>
            <nav class="mt-4">
                <a href="/admin/dashboard" class="block px-4 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
                    <i class="fas fa-dashboard mr-2"></i> Dashboard
                </a>
                <a href="/admin/products" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-users mr-2"></i> Users
                </a>
                <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-comments mr-2"></i> Testimonials
                </a>
                <a href="/admin/bookings" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-2"></i> Bookings
                </a>
                <a href="/" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-globe mr-2"></i> View Website
                </a>
                <a href="/auth/logout" class="block px-4 py-3 text-red-600 hover:bg-gray-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            </header>
            <main class="p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Products</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['total_products']; ?></p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['total_orders']; ?></p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-shopping-cart text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Customers</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['total_customers']; ?></p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-users text-yellow-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Pending Reviews</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['pending_testimonials']; ?></p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-comments text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Recent Orders -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($recentOrders as $order): ?>
                                    <?php $user = $userModel->findById($order['user_id']); ?>
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($order['order_number']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo $user ? htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) : 'N/A'; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            $<?php echo number_format($order['total_amount'], 2); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full <?php 
                                                echo match($order['status']) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'processing' => 'bg-blue-100 text-blue-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="/admin/orders?view=<?php echo $order['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($recentOrders)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No orders found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
