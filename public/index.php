<?php
declare(strict_types=1);

// Optional: Composer autoload (if installed)
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
	require_once $autoloadPath;
}

// Bootstrap config/env and dependencies
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/Utils/Database.php';
require __DIR__ . '/../src/Utils/CSRF.php';
require __DIR__ . '/../src/Utils/Auth.php';

// Models
require __DIR__ . '/../src/Models/BaseModel.php';
require __DIR__ . '/../src/Models/User.php';
require __DIR__ . '/../src/Models/Product.php';
require __DIR__ . '/../src/Models/Category.php';
require __DIR__ . '/../src/Models/Order.php';
require __DIR__ . '/../src/Models/OrderItem.php';
require __DIR__ . '/../src/Models/Testimonial.php';
require __DIR__ . '/../src/Models/Booking.php';
require __DIR__ . '/../src/Models/ProductImage.php';
require __DIR__ . '/../src/Models/Package.php';
require __DIR__ . '/../src/Models/BookingOrder.php';

// Controllers
require __DIR__ . '/../src/Controllers/HomeController.php';
require __DIR__ . '/../src/Controllers/ProductController.php';
require __DIR__ . '/../src/Controllers/AuthController.php';
require __DIR__ . '/../src/Controllers/AdminController.php';
require __DIR__ . '/../src/Controllers/BookingController.php';
require __DIR__ . '/../src/Controllers/PackageController.php';
require __DIR__ . '/../src/Controllers/TestimonialController.php';
require __DIR__ . '/../src/Controllers/ContactController.php';
require __DIR__ . '/../src/Controllers/PaymentController.php';
require __DIR__ . '/../src/Controllers/CategoryController.php';

// Initialize session/CSRF
CSRF::init();

// Normalize route to be relative to the public/ base path (works under XAMPP subdirectory)
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$routePath = substr($requestPath, strlen($basePath));
if ($routePath === false) { $routePath = '/'; }
$path = '/' . ltrim($routePath, '/');
if ($path === '') { $path = '/'; }

switch (true) {
	case $path === '/' || $path === '/home':
		(new HomeController())->index();
		break;

	case $path === '/products':
		(new ProductController())->index();
		break;

	case $path === '/packages':
		(new PackageController())->index();
		break;
    case preg_match('#^/packages/view/(\d+)$#', $path, $m):
        (new PackageController())->show((int)$m[1]);
        break;

	case preg_match('#^/products/show/(\d+)$#', $path, $m):
		(new ProductController())->show((int)$m[1]);
		break;

	case $path === '/api/search':
		header('Content-Type: application/json');
		echo (new ProductController())->searchAPI();
		break;

	case $path === '/auth/login':
		(new AuthController())->login();
		break;

	case $path === '/auth/register':
		(new AuthController())->register();
		break;

	case $path === '/auth/logout':
		(new AuthController())->logout();
		break;

	case $path === '/auth/profile':
		(new AuthController())->profile();
		break;

	case $path === '/auth/orders':
		(new AuthController())->orders();
		break;

	case $path === '/auth/change-password' && $_SERVER['REQUEST_METHOD'] === 'POST':
		(new AuthController())->changePassword();
		break;

	case $path === '/booking' && $_SERVER['REQUEST_METHOD'] === 'GET':
		(new BookingController())->index();
		break;

	case $path === '/booking' && $_SERVER['REQUEST_METHOD'] === 'POST':
		(new BookingController())->submit();
		break;

	case $path === '/admin/dashboard':
		(new AdminController())->dashboard();
		break;

	case $path === '/admin/products':
		(new AdminController())->products();
		break;

	case $path === '/admin/bookings':
		(new AdminController())->bookings();
		break;

    case $path === '/admin/packages':
        (new AdminController())->managePackages();
        break;

    case $path === '/admin/categories':
        (new AdminController())->manageCategory();
        break;

    case $path === '/admin/orders':
        (new AdminController())->orders();
        break;

    case $path === '/testimonials':
        (new TestimonialController())->index();
        break;

    case $path === '/contact' && $_SERVER['REQUEST_METHOD'] === 'GET':
        (new ContactController())->index();
        break;

    case $path === '/contact' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ContactController())->submit();
        break;

    case $path === '/testimonials/submit' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new TestimonialController())->submit();
        break;

    case $path === '/admin/testimonials':
        (new AdminController())->testimonials();
        break;

    case $path === '/admin/users':
        (new AdminController())->users();
        break;

    case preg_match('#^/admin/users/view/(\d+)$#', $path, $m):
        (new AdminController())->viewUser((int)$m[1]);
        break;

    case $path === '/admin/users/delete' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new AdminController())->deleteUser();
        break;

    case $path === '/admin/users/suspend' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new AdminController())->suspendUser();
        break;

    case $path === '/admin/testimonials/approve' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new AdminController())->approveTestimonial();
        break;

    case $path === '/admin/testimonials/reject' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new AdminController())->rejectTestimonial();
        break;

    case $path === '/admin/testimonials/delete' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new AdminController())->deleteTestimonial();
        break;

    case preg_match('#^/admin/testimonials/view/(\d+)$#', $path, $m):
        (new AdminController())->viewTestimonial((int)$m[1]);
        break;

    // Admin booking routes
    case $path === '/admin/bookings':
        (new AdminController())->manageBookings();
        break;
    
    case preg_match('#^/admin/bookings/view/(\d+)$#', $path, $m):
        (new AdminController())->viewBooking((int)$m[1]);
        break;
    
    case preg_match('#^/admin/bookings/verify/(\d+)$#', $path, $m):
        (new AdminController())->verifyBooking((int)$m[1]);
        break;

    // Payment routes
    case $path === '/rental/create':
        (new PaymentController())->createRentalOrder();
        break;
    
    case $path === '/package/book':
        (new PaymentController())->createPackageBooking();
        break;
    
    case $path === '/payment':
        (new PaymentController())->showPayment();
        break;
    
    case $path === '/payment/process':
        (new PaymentController())->processPayment();
        break;
    
    case $path === '/payment-confirmation':
        (new PaymentController())->showConfirmation();
        break;

    case $path === '/api/check_availability':
        require __DIR__ . '/../api/check_availability.php';
        break;

    // Category management routes
    case $path === '/admin/categories':
        (new CategoryController())->index();
        break;
    
    case $path === '/admin/categories/create':
        (new CategoryController())->create();
        break;
    
    case $path === '/admin/categories/store' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new CategoryController())->store();
        break;
    
    case preg_match('#^/admin/categories/edit/(\d+)$#', $path, $m):
        (new CategoryController())->edit((int)$m[1]);
        break;
    
    case preg_match('#^/admin/categories/update/(\d+)$#', $path, $m) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new CategoryController())->update((int)$m[1]);
        break;
    
    case preg_match('#^/admin/categories/delete/(\d+)$#', $path, $m):
        (new CategoryController())->delete((int)$m[1]);
        break;
    
    case preg_match('#^/admin/categories/toggle/(\d+)$#', $path, $m) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new CategoryController())->toggle((int)$m[1]);
        break;

    case $path === '/admin/categories':
        (new CategoryController())->index();
        break;

    default:
        http_response_code(404);
        echo '404 Not Found';
}


