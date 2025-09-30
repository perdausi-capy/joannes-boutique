<?php
// admin/testimonials.php - Admin testimonials management page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Testimonials</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
            </div>
            <nav class="mt-4">
                <a href="/admin/dashboard" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-dashboard mr-2"></i> Dashboard
                </a>
                <a href="/admin/products" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="/admin/orders" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-shopping-cart mr-2"></i> Orders
                </a>
                <a href="/admin/users" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-users mr-2"></i> Users
                </a>
                <a href="/admin/testimonials" class="block px-4 py-3 text-gray-700 bg-blue-50 border-r-2 border-blue-500">
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
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">Manage Testimonials</h1>
                    <div class="text-sm text-gray-600">
                        Total: <?php echo count($testimonials ?? []); ?> testimonials
                    </div>
                </div>
            </header>

            <main class="p-6">
                <!-- Status Filter Tabs -->
                <div class="mb-6 bg-white rounded-lg shadow p-4">
                    <div class="flex space-x-1 border-b">
                        <button onclick="filterTestimonials('all')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
                            All
                        </button>
                        <button onclick="filterTestimonials('pending')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
                            Pending
                        </button>
                        <button onclick="filterTestimonials('approved')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
                            Approved
                        </button>
                        <button onclick="filterTestimonials('rejected')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300">
                            Rejected
                        </button>
                    </div>
                </div>

                <!-- Testimonials List -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <?php if (!empty($testimonials)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rating
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Message
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($testimonials as $testimonial): ?>
                                        <tr class="hover:bg-gray-50" data-status="<?php echo $testimonial['status']; ?>">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gold-100 rounded-full flex items-center justify-center">
                                                        <span class="text-gold-600 font-medium">
                                                            <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                                                        </span>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($testimonial['name']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo htmlspecialchars($testimonial['email']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <?php 
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo $i <= $testimonial['rating'] ? 
                                                            '<i class="fas fa-star text-gold-400"></i>' : 
                                                            '<i class="far fa-star text-gray-300"></i>';
                                                    }
                                                    ?>
                                                    <span class="ml-2 text-sm text-gray-500"><?php echo $testimonial['rating']; ?>/5</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs">
                                                    <?php echo nl2br(htmlspecialchars(substr($testimonial['message'], 0, 100))); ?>
                                                    <?php if (strlen($testimonial['message']) > 100): ?>
                                                        <span class="text-gray-500">...</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php 
                                                    echo match($testimonial['status']) {
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'approved' => 'bg-green-100 text-green-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                ?>">
                                                    <?php echo ucfirst($testimonial['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($testimonial['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <?php if ($testimonial['status'] === 'pending'): ?>
                                                        <button onclick="approveTestimonial(<?php echo $testimonial['id']; ?>)" 
                                                                class="text-green-600 hover:text-green-900 text-xs">
                                                            Approve
                                                        </button>
                                                        <button onclick="rejectTestimonial(<?php echo $testimonial['id']; ?>)" 
                                                                class="text-red-600 hover:text-red-900 text-xs">
                                                            Reject
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="/admin/testimonials/view/<?php echo $testimonial['id']; ?>" 
                                                            class="text-blue-600 hover:text-blue-900 text-xs">
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-comments text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No testimonials found</h3>
                            <p class="text-gray-500">There are no testimonials to manage at this time.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        function filterTestimonials(status) {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update tab styling
            document.querySelectorAll('.border-b-2').forEach(tab => {
                tab.classList.remove('border-transparent', 'border-gray-300');
                tab.classList.add('border-transparent');
            });
        }

        function approveTestimonial(id) {
            if (confirm('Are you sure you want to approve this testimonial?')) {
                fetch('/admin/testimonials/approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo CSRF::generateToken(); ?>'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating testimonial: ' + (data.message || 'Unknown error'));
                    }
                });
            }
        }

        function rejectTestimonial(id) {
            if (confirm('Are you sure you want to reject this testimonial?')) {
                fetch('/admin/testimonials/reject', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo CSRF::generateToken(); ?>'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating testimonial: ' + (data.message || 'Unknown error'));
                    }
                });
            }
        }

    </script>
</body>
</html>
