<?php
$pageTitle = "Contact Us | Joanne's";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');

    .font-serif-elegant { font-family: 'Cormorant Garamond', serif; }

    .contact-gradient-bg {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    .contact-gradient-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(212,175,55,0.15) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(212,175,55,0.1) 0%, transparent 50%);
        animation: pulse 8s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    .contact-card {
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

    .contact-card::before {
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
        box-shadow: 0 0 0 4px rgba(212,175,55,0.1);
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

    .textarea-elegant {
        width: 100%;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #f9fafb;
        padding: 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .textarea-elegant:focus {
        outline: none;
        border-color: #d4af37;
        background: white;
        box-shadow: 0 0 0 4px rgba(212,175,55,0.1);
    }

    .btn-send {
        background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        font-weight: 600;
    }

    .btn-send::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f4d03f 0%, #d4af37 100%);
        transition: left 0.3s;
    }

    .btn-send:hover::before { left: 0; }
    .btn-send:hover {
        box-shadow: 0 10px 30px rgba(212,175,55,0.4);
        transform: translateY(-2px);
    }

    .btn-send span { position: relative; z-index: 1; }

    .alert-message {
        border-left: 4px solid;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        animation: fadeIn 0.5s ease;
    }
    .alert-success {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-color: #16a34a;
        color: #14532d;
    }
    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-color: #dc2626;
        color: #7f1d1d;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .contact-icon-box {
        background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);
        border-radius: 50%;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(212,175,55,0.25);
    }

    .link-gold {
        color: #d4af37;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
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

<div class="contact-gradient-bg px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <div class="brand-logo mx-auto mb-6">
                <i class="fas fa-envelope-open-text text-white text-3xl"></i>
            </div>
            <h1 class="font-serif-elegant text-5xl font-bold text-white mb-4">
                Get in Touch
            </h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                We’d love to hear from you. Send us a message and our team will get back to you shortly.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="contact-card">
                <div class="p-10">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Send us a Message</h2>

                    <?php if ($message): ?>
                        <div class="alert-message <?php echo $message['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
                            <i class="fas <?php echo $message['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                            <?php echo htmlspecialchars($message['text']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="contact" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="input-wrapper">
                                <input type="text" id="name" name="name" class="input-elegant" placeholder="Your Name *" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" class="input-elegant" placeholder="Your Email *" required>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>

                        <div class="input-wrapper">
                            <input type="text" id="subject" name="subject" class="input-elegant" placeholder="Subject">
                            <i class="fas fa-pen input-icon"></i>
                        </div>

                        <div>
                            <textarea id="message" name="message" rows="6" class="textarea-elegant" placeholder="Write your message..." required></textarea>
                        </div>

                        <button type="submit" class="w-full px-6 py-4 btn-send text-white rounded-xl text-lg shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i>
                                Send Message
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <div class="contact-card">
                    <div class="p-10 space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact Information</h3>

                        <div class="flex items-start gap-4">
                            <div class="contact-icon-box">
                                <i class="fas fa-map-marker-alt text-yellow-700"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Address</h4>
                                <p class="text-gray-600">123 Fashion Street<br>City, State 12345</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="contact-icon-box">
                                <i class="fas fa-phone text-yellow-700"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Phone</h4>
                                <p class="text-gray-600">(555) 123-4567</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="contact-icon-box">
                                <i class="fas fa-envelope text-yellow-700"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Email</h4>
                                <p class="text-gray-600">info@joannes.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="contact-icon-box">
                                <i class="fas fa-clock text-yellow-700"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Business Hours</h4>
                                <p class="text-gray-600">Mon–Fri: 9AM–6PM<br>Sat: 10AM–4PM<br>Sun: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Card -->
                <div class="rounded-3xl shadow-lg bg-gradient-to-br from-yellow-500 to-yellow-400 p-10 text-white">
                    <h3 class="text-2xl font-semibold mb-4">Need a Consultation?</h3>
                    <p class="mb-6 text-lg">Schedule a personal fitting or discuss your custom gown design.</p>
                    <a href="booking" class="inline-block bg-white text-yellow-600 font-semibold px-6 py-3 rounded-xl hover:bg-gray-100 transition">
                        Book a Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const alertBox = document.querySelector('.alert-message');
if (alertBox) {
    setTimeout(() => {
        alertBox.style.transition = 'opacity 0.5s ease';
        alertBox.style.opacity = '0';
        setTimeout(() => alertBox.remove(), 500);
    }, 5000);
}
</script>
