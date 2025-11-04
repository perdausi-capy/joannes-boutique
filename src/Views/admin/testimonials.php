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
<style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif-elegant {
            font-family: 'Cormorant Garamond', serif;
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
        }
        
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #d4af37, #f4d03f);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            transform: scaleY(1);
        }
        
        .nav-link:hover {
            background: rgba(212, 175, 55, 0.1);
            padding-left: 1.5rem;
        }
        
        .nav-link.active {
            background: rgba(212, 175, 55, 0.15);
            color: #d4af37;
            font-weight: 600;
        }
        
        .stat-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1a1a1a 0%, #4a4a4a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-gold {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }
        
        .gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        }
        
        .gradient-pink {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
        }
        
        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        }
        
        .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        footer{
            display: none;
        }
        .stat-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background: rgba(212, 175, 55, 0.05);
            transform: scale(1.01);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(249, 250, 251, 1) 100%);
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-text {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .action-link {
            transition: all 0.2s ease;
        }
        
        .action-link:hover {
            transform: translateX(3px);
        }
        nav{
            display:block;
        }
    </style>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto ml-72">
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
                                                    <button onclick="deleteTestimonial(<?php echo $testimonial['id']; ?>)" 
                                                            class="text-gray-600 hover:text-gray-900 text-xs">
                                                        Delete
                                                    </button>
                                                    <a href="admin/testimonials/view/<?php echo $testimonial['id']; ?>" 
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

        function deleteTestimonial(id) {
            if (!confirm('Delete this testimonial? This cannot be undone.')) return;
            fetch('<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?php echo CSRF::generateToken(); ?>'
                },
                body: JSON.stringify({ id })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to delete testimonial');
                }
            });
        }

        function approveTestimonial(id) {
            if (confirm('Are you sure you want to approve this testimonial?')) {
                fetch('<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials/approve', {
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
                fetch('<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials/reject', {
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
