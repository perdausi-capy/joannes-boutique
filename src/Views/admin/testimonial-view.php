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
                    <div class="flex items-center space-x-4">
                    
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
                            <a href="<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
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
                        alert('Testimonial approved successfully! It will now appear on the homepage.');
                        window.location.href = '<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials';
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
                        alert('Testimonial rejected successfully!');
                        window.location.href = '<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials';
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
                    fetch(`<?php echo rtrim(BASE_URL, '/'); ?>/admin/testimonials/${endpoint}`, {
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
