<?php
$pageTitle = "Profile | Joanne's";
$initials = strtoupper(substr($user['first_name'] ?? '', 0, 1) . substr($user['last_name'] ?? '', 0, 1));
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap');
    
    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }
    
    .profile-gradient {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(184, 148, 31, 0.12) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .profile-gradient::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(5deg); }
    }
    
    .profile-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
        transition: left 0.5s;
    }
    
    .profile-card:hover::before {
        left: 100%;
    }
    
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(212, 175, 55, 0.15);
    }
    
    .avatar-gradient {
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        animation: pulse-avatar 3s ease-in-out infinite;
    }
    
    @keyframes pulse-avatar {
        0%, 100% { box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3); }
        50% { box-shadow: 0 10px 40px rgba(212, 175, 55, 0.5); }
    }
    
    .form-card {
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }
    
    .input-enhanced {
        transition: all 0.3s ease;
    }
    
    .input-enhanced:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        transform: translateY(-2px);
    }
    
    .btn-gold-gradient {
        background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-gold-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
        transition: left 0.3s ease;
    }
    
    .btn-gold-gradient:hover::before {
        left: 0;
    }
    
    .btn-gold-gradient span {
        position: relative;
        z-index: 1;
    }
    
    .activity-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .activity-item:hover {
        border-left-color: #d4af37;
        background: rgba(212, 175, 55, 0.05);
        transform: translateX(5px);
    }
    
    .stat-badge {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.2) 100%);
        border: 1px solid rgba(212, 175, 55, 0.3);
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
    
    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        animation: slideInDown 0.4s ease-out;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        animation: slideInDown 0.4s ease-out;
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .section-header {
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #d4af37, #f4d03f);
        border-radius: 2px;
    }
</style>

<div class="profile-gradient min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12 fade-in">
            <h1 class="font-serif-elegant text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                My <span class="text-yellow-600">Profile</span>
            </h1>
            <p class="text-gray-600">Manage your account and view your activity</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Profile Card -->
            <div class="lg:w-1/3 fade-in" style="animation-delay: 0.1s;">
                <div class="profile-card bg-white rounded-2xl shadow-xl p-8 sticky top-8">
                    <div class="flex flex-col items-center">
                        <div class="avatar-gradient w-32 h-32 rounded-full flex items-center justify-center text-4xl text-white font-bold mb-6">
                            <?php echo $initials; ?>
                        </div>
                        
                        <div class="text-center mb-6">
                            <h2 class="font-serif-elegant text-2xl font-bold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                            </h2>
                            <p class="text-gray-600 mb-2 flex items-center justify-center gap-2">
                                <i class="fas fa-envelope text-yellow-600"></i>
                                <?php echo htmlspecialchars($user['email']); ?>
                            </p>
                            <?php if (!empty($user['phone'])): ?>
                                <p class="text-gray-600 mb-2 flex items-center justify-center gap-2">
                                    <i class="fas fa-phone text-yellow-600"></i>
                                    <?php echo htmlspecialchars($user['phone']); ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-500 mt-3 flex items-center justify-center gap-2">
                                <i class="fas fa-calendar text-yellow-600"></i>
                                Member since <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?>
                            </p>
                        </div>
                        
                        <div class="w-full space-y-3">
                            <div class="stat-badge rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-gray-900"><?php echo count($orders ?? []); ?></div>
                                <div class="text-sm text-gray-600">Total Orders</div>
                            </div>
                            <div class="stat-badge rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-gray-900"><?php echo count($bookings ?? []); ?></div>
                                <div class="text-sm text-gray-600">Bookings</div>
                            </div>
                        </div>
                        
                        <a href="auth/logout" class="mt-6 flex items-center gap-2 text-red-600 hover:text-red-700 font-semibold transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Forms & Activity -->
            <div class="lg:w-2/3 space-y-8">
                <!-- Profile Update Form -->
                <div class="form-card bg-white rounded-2xl shadow-xl p-8 fade-in" style="animation-delay: 0.2s;">
                    <h2 class="section-header font-serif-elegant text-2xl font-bold text-gray-900 mb-6">
                        Update Profile
                    </h2>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert-success mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span><?php echo htmlspecialchars($success); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert-error mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="auth/profile" class="space-y-5">
                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-yellow-600 mr-1"></i>
                                    First Name
                                </label>
                                <input type="text" name="first_name" required
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none"
                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-yellow-600 mr-1"></i>
                                    Last Name
                                </label>
                                <input type="text" name="last_name" required
                                       class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none"
                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-yellow-600 mr-1"></i>
                                Phone Number
                            </label>
                            <input type="text" name="phone"
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none"
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-yellow-600 mr-1"></i>
                                Email Address
                            </label>
                            <input type="email" disabled
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 bg-gray-50 text-gray-500"
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>
                        
                        <button type="submit" class="btn-gold-gradient w-full px-6 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i>
                                Update Profile
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Password Change Form -->
                <div class="form-card bg-white rounded-2xl shadow-xl p-8 fade-in" style="animation-delay: 0.3s;">
                    <h2 class="section-header font-serif-elegant text-2xl font-bold text-gray-900 mb-6">
                        Change Password
                    </h2>
                    
                    <?php if (!empty($pw_success)): ?>
                        <div class="alert-success mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span><?php echo htmlspecialchars($pw_success); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($pw_error)): ?>
                        <div class="alert-error mb-6 p-4 text-white rounded-xl flex items-center gap-3 shadow-lg">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                            <span><?php echo htmlspecialchars($pw_error); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="auth/change-password" class="space-y-5">
                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-yellow-600 mr-1"></i>
                                Current Password
                            </label>
                            <input type="password" name="current_password" required
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-key text-yellow-600 mr-1"></i>
                                New Password
                            </label>
                            <input type="password" name="new_password" required
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-yellow-600 mr-1"></i>
                                Confirm New Password
                            </label>
                            <input type="password" name="confirm_password" required
                                   class="input-enhanced w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:outline-none">
                        </div>
                        
                        <button type="submit" class="btn-gold-gradient w-full px-6 py-4 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-shield-alt"></i>
                                Change Password
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Recent Activity -->
                <div class="form-card bg-white rounded-2xl shadow-xl p-8 fade-in" style="animation-delay: 0.4s;">
                    <h2 class="section-header font-serif-elegant text-2xl font-bold text-gray-900 mb-6">
                        Recent Activity
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Orders -->
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-shopping-bag text-yellow-600 text-xl"></i>
                                <h3 class="font-semibold text-gray-900">Recent Orders</h3>
                            </div>
                            <?php if (!empty($orders)): ?>
                                <ul class="space-y-3">
                                    <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                                        <li class="activity-item p-3 rounded-lg">
                                            <div class="font-semibold text-yellow-600 text-sm">#<?php echo htmlspecialchars($order['order_number']); ?></div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                            </div>
                                            <div class="text-sm font-bold text-gray-900 mt-1">
                                                ₱<?php echo number_format($order['total_amount'], 2); ?>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="inline-block px-2 py-1 bg-gray-100 rounded-full">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-gray-400 text-sm">No orders yet</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Bookings -->
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                                <h3 class="font-semibold text-gray-900">Recent Bookings</h3>
                            </div>
                            <?php if (!empty($bookings)): ?>
                                <ul class="space-y-3">
                                    <?php foreach (array_slice($bookings, 0, 3) as $booking): ?>
                                        <li class="activity-item p-3 rounded-lg">
                                            <div class="font-semibold text-yellow-600 text-sm">
                                                <?php echo htmlspecialchars($booking['service_type']); ?>
                                            </div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                <?php echo date('M d, Y', strtotime($booking['created_at'])); ?>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="inline-block px-2 py-1 bg-gray-100 rounded-full">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-gray-400 text-sm">No bookings yet</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Testimonials -->
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-star text-yellow-600 text-xl"></i>
                                <h3 class="font-semibold text-gray-900">Your Reviews</h3>
                            </div>
                            <?php if (!empty($testimonials)): ?>
                                <ul class="space-y-3">
                                    <?php foreach (array_slice($testimonials, 0, 3) as $testimonial): ?>
                                        <li class="activity-item p-3 rounded-lg">
                                            <div class="flex gap-1 mb-1">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star text-xs <?php echo $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="text-xs text-gray-600 italic line-clamp-2">
                                                "<?php echo htmlspecialchars(mb_strimwidth($testimonial['message'], 0, 50, '...')); ?>"
                                            </p>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="inline-block px-2 py-1 bg-gray-100 rounded-full">
                                                    <?php echo ucfirst($testimonial['status']); ?>
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-comments text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-gray-400 text-sm">No reviews yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>