<?php
// src/Views/admin/dashboard.php
// Enhanced admin dashboard with modern design

// Get dashboard statistics
$productModel = new Product();
$orderModel = new Order();
$userModel = new User();
$testimonialModel = new Testimonial();
require_once __DIR__ . '/../../Models/Package.php';
require_once __DIR__ . '/../../Models/BookingOrder.php';
$packageModel = new Package();
$bookingOrderModel = new BookingOrder();

$stats = [
    'total_products' => $productModel->count(),
    'total_orders' => $orderModel->count(),
    'total_customers' => $userModel->count(['role' => 'customer']),
    'pending_testimonials' => $testimonialModel->count(['status' => 'pending']),
    'total_packages' => $packageModel->count(),
    'verified_bookings' => count($bookingOrderModel->findAllVerified())
];

// Get recent verified bookings only
$recentBookings = $bookingOrderModel->findAllVerified();
usort($recentBookings, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Joanne's</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        .refresh-indicator { animation: pulse 1s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="flex-1 ml-72" x-data="dashboardManager()" x-init="init()">
            <main class="p-8">
                <!-- Header -->
            <header class="">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-serif-elegant font-bold text-gray-900">Dashboard Overview</h1>
                            <p class="text-gray-600 mt-1">Monitor your business at a glance</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                                <div class="text-xs text-gray-500">Today</div>
                                <div class="font-semibold text-gray-900"><?php echo date('M d, Y'); ?></div>
                            </div>
                            <div class="text-xs text-gray-500 flex items-center gap-2">
                                <i class="fas fa-sync refresh-indicator" x-show="isRefreshing"></i>
                                <span>Auto-refresh: <span x-text="isRefreshing ? 'ON' : 'OFF'"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Products</p>
                                <p class="stat-number"><?php echo $stats['total_products']; ?></p>
                            </div>
                            <div class="icon-wrapper gradient-blue">
                                <i class="fas fa-box text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">12%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.1s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Packages</p>
                                <p class="stat-number"><?php echo $stats['total_packages']; ?></p>
                            </div>
                            <div class="icon-wrapper gradient-pink">
                                <i class="fas fa-gift text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">8%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.2s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Total Customers</p>
                                <p class="stat-number"><?php echo $stats['total_customers']; ?></p>
                            </div>
                            <div class="icon-wrapper gradient-gold">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">24%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.3s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Pending Reviews</p>
                                <p class="stat-number"><?php echo $stats['pending_testimonials']; ?></p>
                            </div>
                            <div class="icon-wrapper gradient-purple">
                                <i class="fas fa-comments text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-yellow-500 font-semibold">Needs attention</span>
                        </div>
                    </div>

                    <div class="stat-card fade-in bg-white p-6 rounded-2xl shadow-lg border border-gray-100" style="animation-delay: 0.4s;">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 mb-1">Verified Orders</p>
                                <p class="stat-number" x-text="verifiedBookingsCount"></p>
                            </div>
                            <div class="icon-wrapper gradient-green">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-500 font-semibold">18%</span>
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 fade-in" style="animation-delay: 0.6s;">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-serif-elegant font-bold text-gray-900">Recent Orders</h2>
                                <p class="text-sm text-gray-600 mt-1">Latest verified bookings and rentals orders</p>
                            </div>
                            <a href="admin/orders" class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg text-sm">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order #</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Type</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Customer</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <!-- <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th> -->
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="bookingsTableBody">
                                <template x-for="booking in recentBookings.slice(0, 5)" :key="booking.order_id">
                                    <tr class="table-row">
                                        <td class="px-8 py-4">
                                            <span class="font-mono font-semibold text-sm text-gray-900" x-text="'#' + booking.order_id"></span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <span class="status-badge" :class="booking.order_type === 'rental' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'">
                                                <i :class="booking.order_type === 'rental' ? 'fas fa-clock' : 'fas fa-gift'" class="mr-1"></i>
                                                <span x-text="booking.order_type.charAt(0).toUpperCase() + booking.order_type.slice(1)"></span>
                                            </span>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-white text-xs font-bold" x-text="booking.contact_name.charAt(0).toUpperCase()"></span>
                                                </div>
                                                <span class="font-medium text-gray-900" x-text="booking.contact_name"></span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <span class="font-bold text-gray-900">₱<span x-text="parseFloat(booking.total_amount).toFixed(2)"></span></span>
                                        </td>
                                        <!-- <td class="px-8 py-4">
                                            <span class="status-badge bg-blue-100 text-blue-700">
                                                <i class="fas fa-check-double mr-1"></i>
                                                <span x-text="booking.payment_status.charAt(0).toUpperCase() + booking.payment_status.slice(1)"></span>
                                            </span>
                                        </td> -->
                                        <td class="px-8 py-4 text-sm text-gray-600" x-text="new Date(booking.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})"></td>
                                        <td class="px-8 py-4">
                                            <a :href="'admin/bookings/view/' + booking.order_id" class="action-link text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="recentBookings.length === 0">
                                    <td colspan="7" class="px-8 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                            <p class="text-gray-500 font-medium">No verified bookings yet</p>
                                            <p class="text-gray-400 text-sm mt-1">Verified bookings will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    function dashboardManager() {
        return {
            recentBookings: <?= json_encode($recentBookings ?? []); ?>,
            verifiedBookingsCount: <?= $stats['verified_bookings']; ?>,
            isRefreshing: false,
            refreshInterval: null,
            async refreshData() {
                try {
                    const response = await fetch('../api/get_verified_bookings.php');
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.recentBookings = result.data;
                        this.verifiedBookingsCount = result.count;
                    }
                } catch (error) {
                    console.error('Error refreshing dashboard:', error);
                }
            },
            init() {
                this.isRefreshing = true;
                this.refreshData();
                this.refreshInterval = setInterval(() => this.refreshData(), 5000);
            },
            destroy() {
                if (this.refreshInterval) clearInterval(this.refreshInterval);
            }
        }
    }
    </script>
</body>
</html>