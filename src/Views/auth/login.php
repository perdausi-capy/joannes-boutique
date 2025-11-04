<?php
$pageTitle = "Login | Joanne's";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');
    
    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }
    
    .login-gradient-bg {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }
    
    .login-gradient-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
        animation: pulse 8s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    .login-card {
        background: white;
        border-radius: 32px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        position: relative;
        animation: slideUp 0.6s ease;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #d4af37 0%, #f4d03f 50%, #d4af37 100%);
    }
    
    .input-elegant {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f9fafb;
    }
    
    .input-elegant:focus {
        outline: none;
        border-color: #d4af37;
        background: white;
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        transition: color 0.3s ease;
    }
    
    .input-elegant:focus + .input-icon {
        color: #d4af37;
    }
    
    .btn-login {
        background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
        transition: left 0.3s;
    }
    
    .btn-login:hover::before {
        left: 0;
    }
    
    .btn-login:hover {
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        transform: translateY(-2px);
    }
    
    .btn-login span {
        position: relative;
        z-index: 1;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #dc2626;
        animation: shake 0.5s ease;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .brand-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #9ca3af;
        font-size: 0.875rem;
        margin: 2rem 0;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .divider span {
        padding: 0 1rem;
    }
    
    .link-gold {
        color: #d4af37;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        display: inline-block;
    }
    
    .link-gold::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #d4af37 0%, #f4d03f 100%);
        transition: width 0.3s ease;
    }
    
    .link-gold:hover::after {
        width: 100%;
    }
    
    .link-gold:hover {
        color: #b8941f;
    }
    
    .decorative-pattern {
        position: absolute;
        opacity: 0.03;
        font-size: 300px;
        color: #d4af37;
        z-index: 0;
        pointer-events: none;
    }
    
    .pattern-1 { top: -100px; right: -100px; transform: rotate(15deg); }
    .pattern-2 { bottom: -100px; left: -100px; transform: rotate(-15deg); }
</style>

<div class="login-gradient-bg flex items-center justify-center px-4 py-12">
    <div class="decorative-pattern pattern-1">
        <i class="fas fa-crown"></i>
    </div>
    <div class="decorative-pattern pattern-2">
        <i class="fas fa-gem"></i>
    </div>
    
    <div class="w-full max-w-md relative z-10">
        <!-- Brand Logo -->
        <div class="brand-logo">
            <i class="fas fa-crown text-white text-3xl"></i>
        </div>
        
        <!-- Welcome Text -->
        <div class="text-center mb-8">
            <h1 class="font-serif-elegant text-4xl md:text-5xl font-bold text-white mb-3">
                Welcome Back
            </h1>
            <p class="text-gray-400 text-lg">
                Sign in to access your account
            </p>
        </div>
        
        <!-- Login Card -->
        <div class="login-card">
            <div class="p-8 md:p-10">
                <?php if (!empty($error)): ?>
                    <div class="alert-error mb-6 p-4 rounded-xl flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-red-800 mb-1">Authentication Failed</p>
                            <p class="text-red-700 text-sm"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="post" action="auth/login" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                    
                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address
                        </label>
                        <div class="input-wrapper">
                            <input type="email" 
                                   name="email" 
                                   class="input-elegant" 
                                   placeholder="you@example.com"
                                   required
                                   autocomplete="email">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="password" 
                                   class="input-elegant" 
                                   placeholder="Enter your password"
                                   required
                                   autocomplete="current-password">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm link-gold">Forgot password?</a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full px-6 py-4 btn-login text-white rounded-xl font-semibold text-lg shadow-lg">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fas fa-sign-in-alt"></i>
                            Sign In
                        </span>
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>
                
                <!-- Alternative Actions -->
                <div class="text-center space-y-3">
                    <p class="text-gray-600 text-sm">
                        Don't have an account? 
                        <a href="auth/register" class="link-gold">Create one now</a>
                    </p>
                    <a href="/" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors text-sm">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Security Badge -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full backdrop-blur-sm border border-white/20">
                <i class="fas fa-shield-alt text-yellow-400"></i>
                <span class="text-white text-sm">Secure encrypted connection</span>
            </div>
        </div>
    </div>
</div>

<script>
// Add focus animation to inputs
document.querySelectorAll('.input-elegant').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});

// Auto-dismiss error after 5 seconds
const errorAlert = document.querySelector('.alert-error');
if (errorAlert) {
    setTimeout(() => {
        errorAlert.style.transition = 'opacity 0.5s ease';
        errorAlert.style.opacity = '0';
        setTimeout(() => errorAlert.remove(), 500);
    }, 5000);
}
</script>