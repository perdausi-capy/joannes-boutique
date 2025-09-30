<?php
// admin/testimonial-view.php - Admin testimonial detail view
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Testimonial Details</title>
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
                    <div class="flex items-center space-x-4">
                        <a href="/admin/testimonials" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Testimonials
                        </a>
                        <h1 class="text-2xl font-semibold text-gray-800"> Testimonial Details</h1>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-gold-100 rounded-full flex items-center justify-center">
                                <span class="text-gold-600 font-medium text-xl">
                                    <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($testimonial['name']); ?></h2>
                                <p class="text-gray-600"><?php echo htmlspecialchars($testimonial['email']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo date('F j, Y \a\t g:i A', strtotime($testimonial['created_at'])); ?></p>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex items-center">
                                <?php 
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $testimonial['rating'] ? 
                                        '<i class="fas fa-star text-gold-400 text-xl mx-1"></i>' : 
                                        '<i class="far fa-star text-gray-300 text-xl mx-1"></i>';
                                }
                                ?>
                                <span class="ml-3 text-lg font-medium text-gray-700"><?php echo $testimonial['rating']; ?>/5</span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Message</label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="whitespace-pre-wrap text-gray-800"><?php echo nl2br(htmlspecialchars($testimonial['message'])); ?></p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <span class="px-3 py-1 text-sm font-medium rounded-full <?php 
                                echo match($testimonial['status']) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>">
                                <?php echo ucfirst($testimonial['status']); ?>
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3">
                            <a href="/admin/testimonials" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                                <i class="fas fa-arrow-left mr-2"></i> Back to List
                            </a>
                            <?php if ($testimonial['status'] === 'pending'): ?>
                                <button onclick="approveTestimonial(<?php echo $testimonial['id']; ?>)" 
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check mr-2"></i> Approve
                                </button>
                                <button onclick="rejectTestimonial(<?php echo $testimonial['id']; ?>)" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-times mr-2"></i> Reject
                                </button>
                            <?php else: ?>
                                <button onclick="changeStatus(<?php echo $testimonial['id']; ?>)" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-edit mr-2"></i> Change Status
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
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
                        alert('Testimonial approved successfully! It will now appear on the homepage.');
                        window.location.href = '/admin/testimonials';
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
                        alert('Testimonial rejected successfully!');
                        window.location.href = '/admin/testimonials';
                    } else {
                        alert('Error updating testimonial: ' + (data.message || 'Unknown error'));
                    }
                });
            }
        }

        function changeStatus(id) {
            const newStatus = prompt('Enter new status (pending, approved, rejected):');
            if (newStatus && ['pending', 'approved', 'rejected'].includes(newStatus)) {
                const endpoint = newStatus === 'approved' ? 'approve' : 
                                newStatus === 'rejected' ? 'reject' : null;
                
                if (endpoint) {
                    fetch(`/admin/testimonials/${endpoint}`, {
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
                            alert(`Testimonial ${newStatus} successfully!`);
                            window.location.reload();
                        } else {
                            alert('Error updating testimonial');
                        }
                    });
                }
            }
        }
    </script>
</body>
</html>
