<?php
$pageTitle = "Register | Joanne's";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');

    .font-serif-elegant {
        font-family: 'Cormorant Garamond', serif;
    }

    .register-gradient-bg {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    .register-gradient-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    .register-card {
        background: white;
        border-radius: 32px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        position: relative;
        animation: slideUp 0.6s ease;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(50px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .register-card::before {
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
        background: #f9fafb;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .input-elegant:focus {
        outline: none;
        border-color: #d4af37;
        background: white;
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    }

    .input-wrapper { position: relative; }
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        transition: color 0.3s ease;
    }
    .input-elegant:focus + .input-icon { color: #d4af37; }

    .input-helper {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .btn-register {
        background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-weight: 600;
    }

    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
        transition: left 0.3s;
    }

    .btn-register:hover::before { left: 0; }
    .btn-register:hover {
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        transform: translateY(-2px);
    }
    .btn-register span { position: relative; z-index: 1; }

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

    .link-gold {
        color: #d4af37;
        font-weight: 600;
        position: relative;
        transition: all 0.3s ease;
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

    .link-gold:hover::after { width: 100%; }
    .link-gold:hover { color: #b8941f; }
</style>

<div class="register-gradient-bg flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md relative z-10">
        <div class="brand-logo">
            <i class="fas fa-gem text-white text-3xl"></i>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-serif-elegant text-4xl md:text-5xl font-bold text-white mb-3">
                Create an Account
            </h1>
            <p class="text-gray-400 text-lg">
                Join Joanne's and start your journey in elegance
            </p>
        </div>

        <div class="register-card">
            <div class="p-8 md:p-10">
                <?php if (!empty($errors)): ?>
                    <div class="alert-error mb-6 p-4 rounded-xl flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-red-800 mb-1">Registration Error</p>
                            <?php foreach ($errors as $error): ?>
                                <p class="text-red-700 text-sm"><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="post" action="auth/register" class="space-y-6" id="registerForm">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="input-wrapper">
                            <input type="text" name="first_name" class="input-elegant" placeholder="First Name" required value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="last_name" class="input-elegant" placeholder="Last Name" required value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="input-wrapper">
                        <input type="email" name="email" class="input-elegant" placeholder="you@example.com" required value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>

                    <div class="input-wrapper">
                        <input type="text" name="phone" id="phoneInput" class="input-elegant" placeholder="Phone (11 digits)" maxlength="11" inputmode="numeric" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                        <i class="fas fa-phone input-icon"></i>
                        <div class="input-helper">Philippine phone number (11 digits, e.g. 09123456789)</div>
                    </div>

                    <div class="input-wrapper">
                        <input type="password" name="password" class="input-elegant" placeholder="Password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>

                    <div class="input-wrapper">
                        <input type="password" name="confirm_password" class="input-elegant" placeholder="Confirm Password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>

                    <button type="submit" class="w-full px-6 py-4 btn-register text-white rounded-xl text-lg shadow-lg">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Register Now
                        </span>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        Already have an account?
                        <a href="auth/login" class="link-gold">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-dismiss error alert
const errorAlert = document.querySelector('.alert-error');
if (errorAlert) {
    setTimeout(() => {
        errorAlert.style.transition = 'opacity 0.5s ease';
        errorAlert.style.opacity = '0';
        setTimeout(() => errorAlert.remove(), 500);
    }, 5000);
}

// Phone number validation - only allow digits and limit to 11
const phoneInput = document.getElementById('phoneInput');
if (phoneInput) {
    phoneInput.addEventListener('input', (e) => {
        // Remove any non-digit characters
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Limit to 11 digits
        if (e.target.value.length > 11) {
            e.target.value = e.target.value.slice(0, 11);
        }
    });

    // Validate on form submit
    document.getElementById('registerForm').addEventListener('submit', (e) => {
        const phone = phoneInput.value.trim();
        
        // If phone is provided, it must be exactly 11 digits
        if (phone && phone.length !== 11) {
            e.preventDefault();
            alert('Phone number must be exactly 11 digits (e.g., 09123456789)');
            phoneInput.focus();
        }
    });
}
</script>