# Critical Security Fixes - Implementation Guide

This guide provides ready-to-use code for the most critical security fixes.

---

## 1. File Upload Security (CRITICAL)

### Create: `src/Utils/FileUploadValidator.php`

```php
<?php
class FileUploadValidator {
    private static $allowedImageTypes = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'image/webp' => ['webp']
    ];
    
    private static $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];
    
    public static function validateImageUpload($file, $maxSize = null) {
        $maxSize = $maxSize ?? MAX_FILE_SIZE;
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $errors[] = 'File exceeds maximum size of ' . ($maxSize / 1024 / 1024) . 'MB';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Check file size (also check if empty)
        if ($file['size'] === 0) {
            $errors[] = 'File is empty';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Get real MIME type (not from $_FILES which can be spoofed)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        // Validate MIME type
        if (!in_array($realMimeType, self::$allowedMimeTypes)) {
            $errors[] = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Validate extension matches MIME type
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!isset(self::$allowedImageTypes[$realMimeType]) || 
            !in_array($extension, self::$allowedImageTypes[$realMimeType])) {
            $errors[] = 'File extension does not match file type';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Validate it's actually an image (additional check)
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $errors[] = 'File is not a valid image';
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Check image dimensions (optional - prevent extremely large images)
        $maxWidth = 5000;
        $maxHeight = 5000;
        if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
            $errors[] = "Image dimensions too large. Maximum: {$maxWidth}x{$maxHeight}px";
            return ['valid' => false, 'errors' => $errors];
        }
        
        return [
            'valid' => true,
            'mime_type' => $realMimeType,
            'extension' => $extension,
            'width' => $imageInfo[0],
            'height' => $imageInfo[1]
        ];
    }
    
    public static function generateSafeFilename($prefix = 'file', $extension = '') {
        // Generate cryptographically secure random filename
        $randomBytes = bin2hex(random_bytes(16));
        $timestamp = time();
        return $prefix . '_' . $timestamp . '_' . $randomBytes . ($extension ? '.' . $extension : '');
    }
    
    public static function moveUploadedFile($file, $destination) {
        // Ensure directory exists
        $dir = dirname($destination);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Move file
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return false;
        }
        
        // Set safe permissions (readable by web server, not executable)
        chmod($destination, 0644);
        
        return true;
    }
}
```

### Update: `src/Controllers/AdminController.php`

Replace the file upload sections (around lines 36-40, 76-86, 275-278) with:

```php
// Replace this old code:
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = UPLOAD_PATH;
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageFileName = uniqid('prod_', true) . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageFileName);
}

// With this:
require_once __DIR__ . '/../Utils/FileUploadValidator.php';
$imageFileName = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $validation = FileUploadValidator::validateImageUpload($_FILES['image']);
    
    if (!$validation['valid']) {
        $message = '<span class="text-red-600">Upload error: ' . implode(', ', $validation['errors']) . '</span>';
    } else {
        $uploadDir = UPLOAD_PATH;
        $imageFileName = FileUploadValidator::generateSafeFilename('prod', $validation['extension']);
        $destination = $uploadDir . $imageFileName;
        
        if (!FileUploadValidator::moveUploadedFile($_FILES['image'], $destination)) {
            $message = '<span class="text-red-600">Failed to save uploaded file</span>';
            $imageFileName = '';
        }
    }
}
```

---

## 2. API Authentication Middleware

### Create: `src/Middleware/AuthMiddleware.php`

```php
<?php
require_once __DIR__ . '/../Utils/Auth.php';

class AuthMiddleware {
    public static function requireAuth() {
        if (!Auth::isLoggedIn()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Authentication required'
            ]);
            exit;
        }
    }
    
    public static function requireAdmin() {
        self::requireAuth();
        if (!Auth::isAdmin()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Admin access required'
            ]);
            exit;
        }
    }
    
    public static function optionalAuth() {
        // Allows endpoint to work with or without auth
        return Auth::isLoggedIn();
    }
}
```

### Update: `api/get_bookings.php`

```php
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

// Require admin authentication
AuthMiddleware::requireAdmin();

// Rest of your existing code...
```

### Update: `api/create_payment.php`

Add at the top (after config includes):
```php
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';

// Require user authentication
AuthMiddleware::requireAuth();
```

---

## 3. Input Validation Class

### Create: `src/Utils/InputValidator.php`

```php
<?php
class InputValidator {
    public static function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function sanitizeString($string, $maxLength = null) {
        $string = trim($string);
        $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        if ($maxLength && strlen($string) > $maxLength) {
            $string = substr($string, 0, $maxLength);
        }
        return $string;
    }
    
    public static function validatePhone($phone) {
        // Remove common phone number characters
        $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
        // Validate: 7-15 digits
        return preg_match('/^\d{7,15}$/', $cleaned);
    }
    
    public static function sanitizePhone($phone) {
        return preg_replace('/[^\d\+\-\(\)\s]/', '', trim($phone));
    }
    
    public static function validatePrice($price) {
        return is_numeric($price) && $price >= 0 && $price <= 999999.99;
    }
    
    public static function sanitizePrice($price) {
        return filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    public static function validateInteger($value, $min = null, $max = null) {
        if (!is_numeric($value)) {
            return false;
        }
        $int = (int)$value;
        if ($min !== null && $int < $min) return false;
        if ($max !== null && $int > $max) return false;
        return true;
    }
    
    public static function sanitizeInteger($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
    
    public static function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    public static function validateRequired($value) {
        return !empty($value) && trim($value) !== '';
    }
}
```

### Usage Example in Controllers:

```php
// Instead of:
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

// Use:
require_once __DIR__ . '/../Utils/InputValidator.php';
$email = InputValidator::sanitizeEmail($_POST['email'] ?? '');
if (!InputValidator::validateEmail($email)) {
    $errors[] = 'Invalid email address';
}
```

---

## 4. Rate Limiting for Login

### Create: `src/Utils/RateLimiter.php`

```php
<?php
class RateLimiter {
    private $cacheDir;
    
    public function __construct($cacheDir = null) {
        $this->cacheDir = $cacheDir ?? sys_get_temp_dir() . '/rate_limit/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }
    
    public function check($identifier, $maxAttempts = 5, $timeWindow = 300) {
        $file = $this->cacheDir . md5($identifier) . '.txt';
        $now = time();
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            
            // Check if time window has passed
            if ($now - $data['first_attempt'] > $timeWindow) {
                // Reset
                unlink($file);
                return ['allowed' => true, 'remaining' => $maxAttempts];
            }
            
            // Check if max attempts reached
            if ($data['attempts'] >= $maxAttempts) {
                $remaining = $timeWindow - ($now - $data['first_attempt']);
                return [
                    'allowed' => false,
                    'remaining' => 0,
                    'retry_after' => $remaining
                ];
            }
            
            // Increment attempt
            $data['attempts']++;
            file_put_contents($file, json_encode($data));
            
            return [
                'allowed' => true,
                'remaining' => $maxAttempts - $data['attempts']
            ];
        }
        
        // First attempt
        file_put_contents($file, json_encode([
            'first_attempt' => $now,
            'attempts' => 1
        ]));
        
        return ['allowed' => true, 'remaining' => $maxAttempts - 1];
    }
    
    public function reset($identifier) {
        $file = $this->cacheDir . md5($identifier) . '.txt';
        if (file_exists($file)) {
            unlink($file);
        }
    }
    
    public function recordSuccess($identifier) {
        $this->reset($identifier);
    }
}
```

### Update: `src/Controllers/AuthController.php`

Add to the `login()` method:

```php
public function login() {
    // Add rate limiting at the start
    require_once __DIR__ . '/../Utils/RateLimiter.php';
    $rateLimiter = new RateLimiter();
    
    $clientId = $_SERVER['REMOTE_ADDR'] . '_' . ($_POST['email'] ?? '');
    $check = $rateLimiter->check($clientId, 5, 300); // 5 attempts per 5 minutes
    
    if (!$check['allowed']) {
        $error = 'Too many login attempts. Please try again in ' . ceil($check['retry_after'] / 60) . ' minutes.';
        $this->render('auth/login', ['error' => $error]);
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        CSRF::requireToken();
        
        // ... existing login code ...
        
        if ($user) {
            // Reset rate limit on successful login
            $rateLimiter->recordSuccess($clientId);
            // ... rest of login logic ...
        } else {
            // Rate limit will track failed attempts automatically
            $error = 'Invalid email or password. ' . 
                    ($check['remaining'] > 0 ? $check['remaining'] . ' attempts remaining.' : '');
        }
    }
    
    // ... rest of method ...
}
```

---

## 5. Enhanced Session Security

### Update: `config/config.php`

Add after session initialization (around line 40):

```php
// Enhanced session security
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session configuration
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_lifetime', 3600); // 1 hour
    ini_set('session.gc_maxlifetime', 3600);
    
    // Only set secure flag if using HTTPS
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
        ini_set('session.cookie_secure', '1');
    }
    
    session_start();
}
```

---

## 6. Fix Duplicate Sidebar Issue

### Update: `src/Views/admin/orders.php`

Remove lines 57-64 (the first duplicate sidebar):

```php
// DELETE these lines (57-64):
<div class="w-72 fixed top-0 left-0 h-screen sidebar-gradient shadow-2xl flex flex-col">
    <div class="p-6 border-b border-gray-700">
        <h1 class="text-2xl font-serif-elegant font-bold logo-text mb-1">Joanne's Admin</h1>
        <p class="text-sm text-gray-400">Welcome back,</p>
        <p class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    </div>
    <!-- Sidebar -->
```

Keep only the second one (lines 65-109).

---

## 7. JSON Output Security

### Create helper function in `config/config.php`:

```php
function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    
    // Ensure no sensitive data leaks
    $response = $data;
    
    // In production, don't expose error details
    if (($_ENV['APP_ENV'] ?? 'development') !== 'development') {
        if (isset($response['error']) && is_string($response['error'])) {
            // Log detailed error but return generic message
            error_log('API Error: ' . $response['error']);
            $response['error'] = 'An error occurred. Please try again later.';
        }
    }
    
    echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    exit;
}
```

### Usage in API files:

```php
// Instead of:
echo json_encode(['success' => false, 'message' => 'Error']);

// Use:
require_once __DIR__ . '/../config/config.php';
json_response(['success' => false, 'message' => 'Error'], 400);
```

---

## Implementation Priority

1. **Week 1:**
   - File upload security (FileUploadValidator)
   - API authentication (AuthMiddleware)
   - Fix duplicate sidebar

2. **Week 2:**
   - Input validation (InputValidator)
   - Rate limiting (RateLimiter)
   - Session security enhancement

3. **Week 3:**
   - JSON response helper
   - Test all changes
   - Deploy to staging

---

## Testing Checklist

After implementing these fixes, test:

- [ ] Upload malicious file (e.g., .php file renamed to .jpg) - should be rejected
- [ ] Try accessing API endpoints without auth - should return 401
- [ ] Attempt login 6+ times rapidly - should be rate limited
- [ ] Test file upload with valid images - should work
- [ ] Test with extremely large image - should be rejected
- [ ] Verify sessions expire after 1 hour
- [ ] Test CSRF token validation still works
- [ ] Test all existing functionality still works

---

## Notes

- Always backup your code before implementing these changes
- Test in a development environment first
- Consider implementing changes incrementally
- Monitor error logs after deployment

---

*For questions or issues, refer to SYSTEM_ANALYSIS.md*

