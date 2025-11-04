# Joanne's Boutique - System Analysis & Improvement Suggestions

## Executive Summary

Your application is a PHP-based e-commerce system for a boutique specializing in gown and suit rentals, plus package bookings. The system uses PDO for database access, has basic security measures in place, but has several areas that could be significantly improved.

---

## 🔒 **SECURITY IMPROVEMENTS** (High Priority)

### 1. **File Upload Security - CRITICAL**
**Current Issues:**
- No file type validation (only checks extension)
- No file size validation beyond MAX_FILE_SIZE constant
- No MIME type verification
- Files are saved with predictable names
- No virus scanning

**Recommendations:**
```php
// Add proper file validation function
function validateUploadedFile($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']) {
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['error' => 'File too large'];
    }
    
    // Verify MIME type (not just extension)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['error' => 'Invalid file type'];
    }
    
    // Validate image dimensions if it's an image
    if (strpos($mimeType, 'image/') === 0) {
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return ['error' => 'Invalid image file'];
        }
    }
    
    return ['success' => true];
}
```

### 2. **CSRF Protection Coverage**
**Current State:** CSRF tokens exist but not consistently used

**Issues:**
- Some forms may not include CSRF tokens
- API endpoints that modify data don't validate CSRF
- JSON endpoints use different validation pattern

**Recommendations:**
- Add CSRF validation to all state-changing operations
- Include CSRF token in API requests where applicable
- Consider using SameSite cookie attribute

### 3. **Input Validation & Sanitization**
**Current State:** Basic sanitization exists but inconsistent

**Issues:**
- Direct use of `$_POST`, `$_GET`, `$_FILES` without comprehensive validation
- JSON data parsing without proper error handling
- Some fields lack validation (e.g., phone numbers, emails in some contexts)

**Recommendations:**
- Create a centralized InputValidator class
- Validate all inputs against whitelists where possible
- Sanitize output consistently (you're doing well with htmlspecialchars in views)
- Add rate limiting for login attempts

### 4. **SQL Injection Prevention**
**Good:** You're using prepared statements almost everywhere ✅

**Issues Found:**
- One instance in `User.php` line 36: `$this->db->query("SHOW COLUMNS...")` - though this is relatively safe, it's still concatenating table names
- Table names in BaseModel are concatenated directly (but protected, so lower risk)

**Recommendations:**
- Use whitelisting for table/column names in dynamic queries
- Consider using database schema introspection libraries

### 5. **Session Security**
**Current State:** Basic session management

**Recommendations:**
```php
// In config.php, add:
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1'); // Only if using HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', '1');
ini_set('session.gc_maxlifetime', 3600); // 1 hour
```

### 6. **Password Security**
**Good:** Using `password_hash()` and `password_verify()` ✅

**Recommendations:**
- Enforce stronger password requirements (minimum 8 chars, require complexity)
- Add password strength meter in UI
- Consider implementing password reset functionality
- Add account lockout after failed login attempts

### 7. **API Endpoint Security**
**Issues:**
- `api/get_bookings.php` - No authentication check
- Some API endpoints don't validate user permissions
- Webhook endpoints need better validation

**Recommendations:**
- Add authentication middleware for all API endpoints
- Implement API rate limiting
- Add logging for API access attempts
- Validate webhook signatures more thoroughly

### 8. **Error Handling & Information Disclosure**
**Issues:**
- Production error handling turns off error display (good) but may log sensitive info
- Error messages sometimes expose system details

**Recommendations:**
- Create a centralized error handler
- Log errors securely without exposing sensitive data
- Use user-friendly error messages in production

---

## 🏗️ **ARCHITECTURE & CODE QUALITY**

### 1. **Routing System**
**Current State:** Large switch statement in `public/index.php`

**Recommendations:**
- Implement a proper router class
- Support for middleware (authentication, validation, etc.)
- Route grouping and RESTful patterns
- Example structure:
```php
$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->post('/auth/login', [AuthController::class, 'login'])
    ->middleware([CSRFMiddleware::class]);
```

### 2. **Dependency Injection**
**Current State:** Controllers create models directly

**Recommendations:**
- Implement dependency injection container
- Pass dependencies through constructors
- Makes testing easier and reduces coupling

### 3. **Separation of Concerns**
**Issues:**
- Controllers contain business logic
- Models sometimes handle presentation concerns
- Mixed responsibilities

**Recommendations:**
- Extract business logic to Service classes
- Keep controllers thin (request/response handling only)
- Use Repository pattern for data access
- Example:
```php
// Service layer
class BookingService {
    public function __construct(
        private BookingOrderRepository $bookingRepo,
        private PaymentService $paymentService
    ) {}
    
    public function createRentalBooking($data): Booking {
        // Business logic here
    }
}
```

### 4. **Configuration Management**
**Current State:** Custom .env parsing

**Recommendations:**
- Use a library like `vlucas/phpdotenv` for proper .env handling
- Type-safe configuration access
- Environment-specific configs

### 5. **Code Duplication**
**Issues:**
- Similar upload logic repeated across controllers
- Duplicate sidebar code in admin views
- Similar validation patterns repeated

**Recommendations:**
- Create FileUpload service
- Extract common view components
- Create reusable validation helpers

---

## 📊 **DATABASE IMPROVEMENTS**

### 1. **Database Schema**
**Recommendations:**
- Add indexes on frequently queried columns (user_id, order_id, status)
- Add foreign key constraints if not present
- Consider adding soft deletes instead of hard deletes
- Add created_at/updated_at timestamps consistently
- Normalize data where appropriate

### 2. **Query Optimization**
**Current State:** Some N+1 query patterns possible

**Recommendations:**
- Use eager loading for related data
- Add query result caching where appropriate
- Consider using query builders for complex queries

### 3. **Migration System**
**Current State:** Manual migration file

**Recommendations:**
- Implement proper migration system (like Laravel/Doctrine)
- Version-controlled schema changes
- Rollback capabilities

---

## 🎨 **FRONTEND IMPROVEMENTS**

### 1. **XSS Prevention**
**Good:** Using `htmlspecialchars()` in views ✅

**Recommendations:**
- Audit all user-generated content output
- Use Content Security Policy (CSP) headers
- Sanitize rich text if you add WYSIWYG editors

### 2. **JavaScript Security**
**Issues Found:**
- Line 279 in `orders.php`: `bookings: <?= json_encode($bookings ?? []); ?>` - Direct PHP to JS
- This is generally safe, but ensure all data is properly escaped

**Recommendations:**
- Consider using a frontend framework (React/Vue)
- API-based architecture for better separation
- Use JSON API endpoints instead of inline data

### 3. **UI/UX**
**Recommendations:**
- Add loading states for async operations
- Better error messages and user feedback
- Responsive design improvements
- Accessibility improvements (ARIA labels, keyboard navigation)

---

## 🚀 **PERFORMANCE IMPROVEMENTS**

### 1. **Caching**
**Recommendations:**
- Implement caching for:
  - Product listings
  - Category data
  - User sessions
- Use Redis or Memcached for session storage
- Add HTTP caching headers

### 2. **Asset Optimization**
**Current State:** Using CDN for some assets (good)

**Recommendations:**
- Minify and combine CSS/JS
- Image optimization and lazy loading
- Use service workers for offline capability

### 3. **Database Queries**
**Recommendations:**
- Add query logging to identify slow queries
- Use database query cache
- Optimize joins and avoid N+1 queries

### 4. **Auto-refresh Optimization**
**Issue Found:** Line 312 in `orders.php` - 5 second auto-refresh

**Recommendations:**
- Use WebSockets or Server-Sent Events instead of polling
- Reduce polling frequency or make it configurable
- Only refresh when needed (on user action)

---

## 📝 **MAINTAINABILITY**

### 1. **Logging System**
**Current State:** Basic error logging

**Recommendations:**
- Implement structured logging (use Monolog)
- Log levels (DEBUG, INFO, WARNING, ERROR)
- Separate logs for different concerns (security, payment, etc.)
- Log rotation and archival

### 2. **Testing**
**Recommendations:**
- Add unit tests for critical functions
- Integration tests for payment flow
- Test file upload security
- Automated testing pipeline

### 3. **Documentation**
**Recommendations:**
- API documentation
- Code comments for complex logic
- Developer setup guide
- Deployment procedures

### 4. **Version Control**
**Issues:**
- `.env` file is modified (should be in .gitignore)
- Many untracked files

**Recommendations:**
- Proper .gitignore
- `.env.example` template
- Ignore uploads directory (except .htaccess if needed)

---

## 💳 **PAYMENT SECURITY**

### 1. **Payment Processing**
**Current State:** Using PayMongo API

**Recommendations:**
- Never log payment details
- Validate all payment webhooks properly
- Store payment IDs only, not full payment data
- Implement idempotency for payment processing
- Add transaction logging (without sensitive data)

### 2. **Payment Flow**
**Recommendations:**
- Add payment confirmation emails
- Better error handling in payment process
- Handle payment timeouts gracefully
- Add payment status webhook retry mechanism

---

## 🔍 **SPECIFIC CODE ISSUES FOUND**

### 1. **Duplicate Sidebar in orders.php**
Lines 57-70 and 65-70 have duplicate sidebar code. Remove one.

### 2. **BaseModel Table Name Concatenation**
Table names are concatenated in SQL. While protected properties help, consider whitelisting.

### 3. **Direct SQL in User Model**
Line 36 in `User.php` uses `query()` instead of prepared statement (though relatively safe here).

### 4. **Missing Input Validation**
Several endpoints don't validate input properly before processing.

### 5. **Inconsistent Error Handling**
Some methods return boolean, others throw exceptions. Standardize.

---

## 📋 **PRIORITY ACTION ITEMS**

### **CRITICAL (Do Immediately):**
1. ✅ Fix file upload security (add MIME validation, file type whitelisting)
2. ✅ Add authentication to all API endpoints
3. ✅ Implement rate limiting for login/payment endpoints
4. ✅ Add comprehensive input validation
5. ✅ Secure session configuration

### **HIGH (Do Soon):**
1. ✅ Implement proper router system
2. ✅ Add service layer for business logic
3. ✅ Database indexing and optimization
4. ✅ Structured logging system
5. ✅ Error handling standardization

### **MEDIUM (Plan For):**
1. ✅ Code refactoring (DRY principle)
2. ✅ Testing framework setup
3. ✅ Frontend framework adoption
4. ✅ Caching implementation
5. ✅ API documentation

### **LOW (Nice to Have):**
1. ✅ Performance monitoring
2. ✅ Advanced analytics
3. ✅ Mobile app API
4. ✅ Multi-language support

---

## 🛠️ **RECOMMENDED TOOLS & LIBRARIES**

1. **Composer Packages:**
   - `vlucas/phpdotenv` - Environment variable management
   - `monolog/monolog` - Logging
   - `respect/validation` - Input validation
   - `firebase/php-jwt` - JWT tokens for API
   - `guzzlehttp/guzzle` - HTTP client for APIs

2. **Development Tools:**
   - PHPUnit for testing
   - PHPStan for static analysis
   - Xdebug for debugging

3. **Frontend:**
   - Consider React/Vue for better UI management
   - Axios for API calls
   - Form validation libraries

---

## 📊 **METRICS TO TRACK**

1. **Security:**
   - Failed login attempts
   - API rate limit hits
   - File upload rejections
   - CSRF token failures

2. **Performance:**
   - Page load times
   - Database query times
   - API response times
   - File upload times

3. **Business:**
   - Conversion rates
   - Payment success rates
   - User retention
   - Popular products/packages

---

## Conclusion

Your system has a solid foundation with proper password hashing, PDO usage, and basic security measures. The main areas needing immediate attention are:

1. **File upload security** (critical)
2. **API endpoint authentication** (critical)
3. **Input validation consistency** (high)
4. **Code organization and architecture** (medium-high)

Focus on the critical items first, then work through the high-priority items. The system is functional but would benefit significantly from these improvements, especially as it scales.


joannes-boutique/
│
├── 📁 admin/
│   └── index.php                          # Admin dashboard entry point
│
├── 📁 api/                                 # API endpoints
│   ├── check_availability.php             # ✅ Rental availability checker (fixed)
│   ├── create_payment.php                 # Payment creation endpoint
│   ├── get_bookings.php                   # Get bookings API
│   ├── get_verified_bookings.php          # Get verified bookings API
│   ├── payment_webhook.php                # PayMongo webhook handler
│   └── paymongo_helper.php                # PayMongo helper functions
│
├── 📁 config/                              # Configuration files
│   ├── config.php                         # Main configuration & constants
│   ├── database.php                       # Database connection (PDO)
│   ├── mail.php                           # Email configuration
│   └── paymongo_config.php                # PayMongo payment config
│
├── 📁 database/                            # Database scripts
│   ├── 2025_11_booking_availability.sql   # Booking availability indexes
│   ├── backfill_product_images.php        # Image migration script
│   ├── joannes_boutique.sql               # Main database dump
│   ├── migrate.php                        # Database migration script
│   └── seed.php                           # Database seeding script
│
├── 📁 public/                              # Public web directory
│   ├── index.php                          # Main entry point/router
│   ├── find_error_log.php                 # Error log finder tool
│   ├── test_log.php                       # Log testing script
│   ├── test-db.php                        # Database test script
│   ├── ourstory.jpg                       # About page image
│   │
│   ├── 📁 assets/                          # Static assets
│   │   ├── background1.jpg
│   │   ├── background2.jpg
│   │   ├── bghome.jpeg
│   │   ├── bghome2.jpeg
│   │   ├── bghome3.jpeg
│   │   ├── bridal.mp4                     # Video asset
│   │   ├── classic.jpeg
│   │   └── Messenger_creation_*.jpeg       # Additional images
│   │
│   └── 📁 uploads/                        # User uploads
│       ├── payment_proofs/                # Payment proof images
│       ├── payments/                      # Payment images (10 files)
│       ├── pkg_bg_*.jpg                   # Package background images
│       ├── prod_*.jpg                     # Product images
│       └── prod_extra_*.jpg               # Product extra images
│
├── 📁 src/                                 # Source code
│   │
│   ├── Database.php                       # Database wrapper class
│   │
│   ├── 📁 Controllers/                    # MVC Controllers
│   │   ├── AdminController.php            # Admin operations
│   │   ├── AuthController.php             # Authentication
│   │   ├── BookingController.php          # Booking management
│   │   ├── CartController.php             # Shopping cart
│   │   ├── ContactController.php          # Contact form
│   │   ├── HomeController.php             # Home page
│   │   ├── PackageController.php          # Package management
│   │   ├── PaymentController.php          # Payment processing ✅ (has availability check)
│   │   ├── ProductController.php          # Product management
│   │   └── TestimonialController.php      # Testimonials
│   │
│   ├── 📁 Models/                         # Data Models
│   │   ├── BaseModel.php                  # Base model class
│   │   ├── Booking.php                    # Booking model
│   │   ├── BookingOrder.php               # ✅ Booking orders (has checkAvailability)
│   │   ├── Cart.php                       # Cart model
│   │   ├── Category.php                   # Category model
│   │   ├── ContactMessage.php             # Contact messages
│   │   ├── Order.php                      # Order model
│   │   ├── OrderItem.php                  # Order items
│   │   ├── Package.php                    # Package model
│   │   ├── Product.php                    # Product model
│   │   ├── ProductImage.php               # Product images
│   │   ├── Testimonial.php                # Testimonials
│   │   └── User.php                       # User model
│   │
│   ├── 📁 Utils/                          # Utility classes
│   │   ├── Auth.php                       # Authentication utilities
│   │   ├── CSRF.php                       # CSRF protection
│   │   └── Database.php                   # Database utilities
│   │
│   └── 📁 Views/                          # View templates
│       │
│       ├── home.php                       # Home page view
│       ├── layout.php                     # Base layout template
│       ├── payment.php                     # Payment page
│       ├── payment-confirmation.php        # Payment confirmation
│       │
│       ├── 📁 admin/                      # Admin views
│       │   ├── booking-view.php           # View single booking
│       │   ├── bookings.txt               # Bookings data
│       │   ├── dashboard.php              # Admin dashboard
│       │   ├── orders.php                 # Orders management
│       │   ├── packages.php               # Packages management
│       │   ├── products.php               # Products management
│       │   ├── testimonial-view.php       # View testimonial
│       │   ├── testimonials.php          # Testimonials management
│       │   ├── user-view.php              # View user
│       │   └── users.php                  # Users management
│       │
│       ├── 📁 auth/                       # Authentication views
│       │   ├── login.php                  # Login page
│       │   ├── orders.php                 # User orders
│       │   ├── profile.php                # User profile
│       │   └── register.php               # Registration
│       │
│       ├── 📁 booking/                    # Booking views
│       │   ├── form.php                   # Booking form
│       │   └── thankyou.php               # Booking confirmation
│       │
│       ├── 📁 cart/                       # Shopping cart views
│       │   ├── checkout.php              # Checkout page
│       │   └── index.php                  # Cart page
│       │
│       ├── 📁 contact/                    # Contact views
│       │   └── index.php                  # Contact page
│       │
│       ├── 📁 packages/                   # Package views
│       │   ├── index.php                  # Packages listing
│       │   └── show.php                   # Package details ✅ (has availability check)
│       │
│       ├── 📁 products/                   # Product views
│       │   ├── index.php                  # Products listing
│       │   └── show.php                   # ✅ Product details (availability message display)
│       │
│       └── 📁 testimonials/               # Testimonial views
│           └── index.php                  # Testimonials page
│
├── 📁 tools/                               # Utility tools
│   └── check_db.php                       # Database check tool
│
├── 📄 Documentation files
│   ├── BOOKING_FLOW_ANALYSIS.md           # Booking flow documentation
│   ├── CRITICAL_FIXES_GUIDE.md            # Critical fixes guide
│   ├── DOUBLE_BOOKING_FIX_SUMMARY.md      # Double booking fix summary
│   ├── QUICK_REFERENCE.md                 # Quick reference guide
│   ├── README.md                          # Main README
│   └── SYSTEM_ANALYSIS.md                 # System analysis doc
│
└── composer.json                          # Composer dependencies

---

*Generated: $(date)*

