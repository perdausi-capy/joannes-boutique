# Booking Flow Analysis & Date Conflict Prevention Guide

## 📋 Current Booking System Overview

### Database Structure

#### `booking_orders` Table
```sql
CREATE TABLE booking_orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    order_type ENUM('rental','package') NOT NULL,
    item_id INT NOT NULL,                    -- References products.id OR packages.package_id
    event_date DATE NULL,                     -- For packages
    rental_start DATE NULL,                   -- For rentals (gowns/suits)
    rental_end DATE NULL,                     -- For rentals (gowns/suits)
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending','paid','verified') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'GCash',
    reference_number VARCHAR(100) NULL,
    proof_image VARCHAR(255) NULL,
    contact_name VARCHAR(100) NULL,
    contact_email VARCHAR(100) NULL,
    contact_phone VARCHAR(20) NULL,
    quantity INT DEFAULT 1,
    size VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

**Key Fields for Conflict Detection:**
- `order_type`: 'rental' or 'package'
- `item_id`: ID of product (gown/suit) or package
- `rental_start` / `rental_end`: Date range for rentals
- `event_date`: Single date for packages
- `payment_status`: 'pending', 'paid', or 'verified'

---

## 🔍 Current Booking Flow

### 1. **Rental Flow (Gowns/Suits)**

**Entry Point:**
- `src/Views/products/show.php` - User clicks "Rent" button
- Form submits to: `src/Controllers/PaymentController.php::createRentalOrder()`

**Flow:**
```
User selects product → 
  Opens rental modal → 
    User enters rental_start, rental_end, contact info → 
      POST to /rental/create → 
        PaymentController::createRentalOrder() → 
          BookingOrder::create() → 
            Redirects to /payment?order_id=X
```

**Fields Stored:**
- `order_type`: 'rental'
- `item_id`: product ID
- `rental_start`: Start date
- `rental_end`: End date
- `payment_status`: 'pending' (initially)

**File:** `src/Controllers/PaymentController.php` (lines 21-81)

---

### 2. **Package Booking Flow**

**Entry Point:**
- `src/Views/packages/show.php` or `index.php` - User books package
- Form submits to: `src/Controllers/PaymentController.php::createPackageBooking()`

**Flow:**
```
User selects package → 
  Enters event_date, number_of_guests, contact info → 
    POST to /package/book → 
      PaymentController::createPackageBooking() → 
        BookingOrder::create() → 
          Redirects to /payment?order_id=X
```

**Fields Stored:**
- `order_type`: 'package'
- `item_id`: package ID
- `event_date`: Event date
- `payment_status`: 'pending' (initially)

**File:** `src/Controllers/PaymentController.php` (lines 83-138)

---

### 3. **Admin Approval/Verification Flow**

**Entry Point:**
- Admin views bookings at `/admin/bookings` or `/admin/orders`
- Admin can verify payment after it's marked as 'paid'

**Flow:**
```
Booking created (status: 'pending') → 
  User uploads payment proof → 
    Status changes to 'paid' → 
      Admin reviews at /admin/bookings → 
        Admin clicks "Verify" → 
          AdminController::verifyBooking() or verifyPayment() → 
            BookingOrder::verifyPayment() → 
              Status changes to 'verified'
```

**Verification Methods:**
- `src/Controllers/AdminController.php::verifyBooking($id)` (line 458)
- `src/Controllers/AdminController.php::manageBookings()` (line 423)
- `src/Models/BookingOrder.php::verifyPayment($orderId)` (line 146)

---

## ⚠️ **Current Issues: NO Date Conflict Detection**

### Problems Identified:

1. **No validation before booking creation**
   - `PaymentController::createRentalOrder()` creates booking without checking for conflicts
   - `PaymentController::createPackageBooking()` creates booking without checking for conflicts

2. **No database constraints**
   - No unique indexes on `(item_id, rental_start, rental_end)` or `(item_id, event_date)`
   - Multiple bookings can exist for the same item on overlapping dates

3. **No frontend validation**
   - Date pickers allow selecting dates that may already be booked
   - No API endpoint to check availability before submission

4. **Status consideration missing**
   - Should prevent conflicts with bookings that are:
     - `pending` (payment not yet received)
     - `paid` (payment received, awaiting verification)
     - `verified` (confirmed booking)

---

## 💡 **Implementation Plan: Date Conflict Detection**

### Phase 1: Add Conflict Detection Method (Backend)

**File:** `src/Models/BookingOrder.php`

Add this method to check for conflicts:

```php
/**
 * Check if an item is available for the given date range
 * 
 * @param int $itemId The product or package ID
 * @param string $orderType 'rental' or 'package'
 * @param string $startDate For rentals: start date, For packages: event_date
 * @param string $endDate For rentals: end date, NULL for packages
 * @param int $excludeOrderId Optional: exclude this order_id (for updates)
 * @return array ['available' => bool, 'conflicting_orders' => array]
 */
public function checkAvailability($itemId, $orderType, $startDate, $endDate = null, $excludeOrderId = null) {
    $conflictingOrders = [];
    
    if ($orderType === 'rental') {
        // Check for overlapping date ranges
        // Conflict exists if:
        // - New start date is between existing start and end
        // - New end date is between existing start and end
        // - New range completely encompasses an existing booking
        // - Existing range completely encompasses the new booking
        
        $sql = "SELECT order_id, rental_start, rental_end, payment_status, contact_name 
                FROM {$this->table} 
                WHERE order_type = 'rental' 
                AND item_id = ? 
                AND payment_status IN ('pending', 'paid', 'verified')
                AND (
                    (? BETWEEN rental_start AND rental_end) OR
                    (? BETWEEN rental_start AND rental_end) OR
                    (rental_start <= ? AND rental_end >= ?)
                )";
        
        $params = [$itemId, $startDate, $endDate, $startDate, $endDate];
        
        if ($excludeOrderId) {
            $sql .= " AND order_id != ?";
            $params[] = $excludeOrderId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $conflictingOrders = $stmt->fetchAll();
        
    } elseif ($orderType === 'package') {
        // For packages, check if event_date conflicts
        // Conflict exists if event_date matches another booking's event_date
        
        $sql = "SELECT order_id, event_date, payment_status, contact_name 
                FROM {$this->table} 
                WHERE order_type = 'package' 
                AND item_id = ? 
                AND event_date = ?
                AND payment_status IN ('pending', 'paid', 'verified')";
        
        $params = [$itemId, $startDate];
        
        if ($excludeOrderId) {
            $sql .= " AND order_id != ?";
            $params[] = $excludeOrderId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $conflictingOrders = $stmt->fetchAll();
    }
    
    return [
        'available' => empty($conflictingOrders),
        'conflicting_orders' => $conflictingOrders
    ];
}

/**
 * Get all booked dates for an item
 * Useful for frontend calendar display
 */
public function getBookedDates($itemId, $orderType, $startDate = null, $endDate = null) {
    $bookedDates = [];
    
    if ($orderType === 'rental') {
        $sql = "SELECT rental_start, rental_end, payment_status 
                FROM {$this->table} 
                WHERE order_type = 'rental' 
                AND item_id = ? 
                AND payment_status IN ('pending', 'paid', 'verified')";
        
        if ($startDate && $endDate) {
            $sql .= " AND (
                (rental_start BETWEEN ? AND ?) OR
                (rental_end BETWEEN ? AND ?) OR
                (rental_start <= ? AND rental_end >= ?)
            )";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$itemId, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$itemId]);
        }
        
        $bookings = $stmt->fetchAll();
        foreach ($bookings as $booking) {
            $start = new DateTime($booking['rental_start']);
            $end = new DateTime($booking['rental_end']);
            $interval = new DateInterval('P1D');
            $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
            
            foreach ($period as $date) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }
        
    } elseif ($orderType === 'package') {
        $sql = "SELECT event_date, payment_status 
                FROM {$this->table} 
                WHERE order_type = 'package' 
                AND item_id = ? 
                AND payment_status IN ('pending', 'paid', 'verified')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$itemId]);
        
        $bookings = $stmt->fetchAll();
        foreach ($bookings as $booking) {
            $bookedDates[] = $booking['event_date'];
        }
    }
    
    return array_unique($bookedDates);
}
```

---

### Phase 2: Add Validation Before Booking Creation

**Update:** `src/Controllers/PaymentController.php`

**In `createRentalOrder()` method (around line 50):**

```php
public function createRentalOrder() {
    // ... existing code ...
    
    $productId = (int)($_POST['product_id'] ?? 0);
    $rentalStart = $_POST['rental_start'] ?? '';
    $rentalEnd = $_POST['rental_end'] ?? '';
    
    // Validate dates
    if (empty($rentalStart) || empty($rentalEnd)) {
        $_SESSION['error'] = 'Please select both start and end dates';
        header('Location: ' . BASE_URL . 'products/show/' . $productId);
        exit;
    }
    
    // Ensure start date is before end date
    if (strtotime($rentalStart) > strtotime($rentalEnd)) {
        $_SESSION['error'] = 'Start date must be before end date';
        header('Location: ' . BASE_URL . 'products/show/' . $productId);
        exit;
    }
    
    // Ensure dates are not in the past
    if (strtotime($rentalStart) < strtotime('today')) {
        $_SESSION['error'] = 'Start date cannot be in the past';
        header('Location: ' . BASE_URL . 'products/show/' . $productId);
        exit;
    }
    
    // Get product details
    $product = $this->productModel->findById($productId);
    if (!$product) {
        $_SESSION['error'] = 'Product not found';
        header('Location: ' . BASE_URL . 'products');
        exit;
    }
    
    // ✅ NEW: Check for date conflicts
    $availability = $this->bookingOrderModel->checkAvailability(
        $productId,
        'rental',
        $rentalStart,
        $rentalEnd
    );
    
    if (!$availability['available']) {
        $conflictMsg = 'This item is already booked for the selected dates. ';
        $conflictMsg .= 'Please choose different dates.';
        
        if (count($availability['conflicting_orders']) > 0) {
            $firstConflict = $availability['conflicting_orders'][0];
            $conflictMsg .= ' Conflicting booking: ' . 
                date('M d', strtotime($firstConflict['rental_start'])) . 
                ' - ' . 
                date('M d, Y', strtotime($firstConflict['rental_end']));
        }
        
        $_SESSION['error'] = $conflictMsg;
        header('Location: ' . BASE_URL . 'products/show/' . $productId);
        exit;
    }
    
    // ... rest of existing code ...
}
```

**In `createPackageBooking()` method (around line 97):**

```php
public function createPackageBooking() {
    // ... existing code ...
    
    $packageId = (int)($_POST['package_id'] ?? 0);
    $eventDate = $_POST['event_date'] ?? '';
    
    // Validate date
    if (empty($eventDate)) {
        $_SESSION['error'] = 'Please select an event date';
        header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
        exit;
    }
    
    // Ensure date is not in the past
    if (strtotime($eventDate) < strtotime('today')) {
        $_SESSION['error'] = 'Event date cannot be in the past';
        header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
        exit;
    }
    
    // Get package details
    $package = $this->packageModel->findById($packageId);
    if (!$package) {
        $_SESSION['error'] = 'Package not found';
        header('Location: ' . BASE_URL . 'packages');
        exit;
    }
    
    // ✅ NEW: Check for date conflicts
    $availability = $this->bookingOrderModel->checkAvailability(
        $packageId,
        'package',
        $eventDate,
        null  // No end date for packages
    );
    
    if (!$availability['available']) {
        $conflictMsg = 'This package is already booked for ' . date('F j, Y', strtotime($eventDate)) . '. ';
        $conflictMsg .= 'Please choose a different date.';
        
        $_SESSION['error'] = $conflictMsg;
        header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
        exit;
    }
    
    // ... rest of existing code ...
}
```

---

### Phase 3: Create Availability API Endpoint

**New File:** `api/check_availability.php`

```php
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/BookingOrder.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'GET method required']);
    exit;
}

$itemId = (int)($_GET['item_id'] ?? 0);
$orderType = $_GET['order_type'] ?? ''; // 'rental' or 'package'
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? null;

if (!$itemId || !$orderType || !$startDate) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters: item_id, order_type, start_date'
    ]);
    exit;
}

if (!in_array($orderType, ['rental', 'package'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid order_type']);
    exit;
}

try {
    $bookingOrderModel = new BookingOrder();
    
    $availability = $bookingOrderModel->checkAvailability(
        $itemId,
        $orderType,
        $startDate,
        $endDate
    );
    
    // Also get all booked dates for calendar display
    $bookedDates = $bookingOrderModel->getBookedDates($itemId, $orderType);
    
    echo json_encode([
        'success' => true,
        'available' => $availability['available'],
        'conflicting_orders' => $availability['conflicting_orders'],
        'booked_dates' => $bookedDates
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error checking availability: ' . $e->getMessage()
    ]);
}
?>
```

**Update:** `public/index.php` - Add route:

```php
case $path === '/api/check_availability':
    require __DIR__ . '/../api/check_availability.php';
    break;
```

---

### Phase 4: Frontend Validation & Calendar Integration

**Update:** `src/Views/products/show.php` - Add JavaScript for real-time availability checking:

```javascript
// Add after the rental form definition
<script>
// Check availability when dates change
document.querySelector('input[name="rental_start"]')?.addEventListener('change', checkAvailability);
document.querySelector('input[name="rental_end"]')?.addEventListener('change', checkAvailability);

async function checkAvailability() {
    const productId = document.getElementById('rentalProductId')?.value;
    const startDate = document.querySelector('input[name="rental_start"]')?.value;
    const endDate = document.querySelector('input[name="rental_end"]')?.value;
    
    if (!productId || !startDate || !endDate) return;
    
    // Ensure start < end
    if (new Date(startDate) > new Date(endDate)) {
        showAvailabilityError('Start date must be before end date');
        return;
    }
    
    try {
        const response = await fetch(`<?php echo BASE_URL; ?>api/check_availability?item_id=${productId}&order_type=rental&start_date=${startDate}&end_date=${endDate}`);
        const data = await response.json();
        
        if (!data.available) {
            showAvailabilityError('These dates are not available. Please choose different dates.');
            // Optionally disable submit button
            document.getElementById('rentalForm')?.querySelector('button[type="submit"]')?.setAttribute('disabled', 'disabled');
        } else {
            hideAvailabilityError();
            document.getElementById('rentalForm')?.querySelector('button[type="submit"]')?.removeAttribute('disabled');
        }
    } catch (error) {
        console.error('Error checking availability:', error);
    }
}

function showAvailabilityError(message) {
    let errorDiv = document.getElementById('availability-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'availability-error';
        errorDiv.className = 'bg-red-100 text-red-700 p-3 rounded-lg mt-2';
        document.getElementById('rentalForm').insertBefore(errorDiv, document.getElementById('rentalForm').firstChild);
    }
    errorDiv.textContent = message;
}

function hideAvailabilityError() {
    const errorDiv = document.getElementById('availability-error');
    if (errorDiv) errorDiv.remove();
}

// Load booked dates on modal open and disable them in date picker
async function loadBookedDates(productId) {
    try {
        const response = await fetch(`<?php echo BASE_URL; ?>api/check_availability?item_id=${productId}&order_type=rental&start_date=&end_date=`);
        const data = await response.json();
        
        if (data.booked_dates && data.booked_dates.length > 0) {
            // Disable booked dates in date picker
            const startInput = document.querySelector('input[name="rental_start"]');
            const endInput = document.querySelector('input[name="rental_end"]');
            
            // Note: Native HTML5 date inputs don't support disabling specific dates
            // Consider using a date picker library like Flatpickr or Pikaday
            // Example with Flatpickr:
            // flatpickr(startInput, {
            //     minDate: "today",
            //     disable: data.booked_dates
            // });
        }
    } catch (error) {
        console.error('Error loading booked dates:', error);
    }
}
</script>
```

---

### Phase 5: Database Indexing for Performance

**Create migration file:** `database/add_booking_indexes.php`

```php
<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';

$db = new Database();
$pdo = $db->connect();

try {
    // Add indexes for faster conflict detection queries
    $pdo->exec("
        CREATE INDEX idx_booking_rental_dates 
        ON booking_orders(order_type, item_id, rental_start, rental_end, payment_status)
    ");
    
    $pdo->exec("
        CREATE INDEX idx_booking_package_date 
        ON booking_orders(order_type, item_id, event_date, payment_status)
    ");
    
    echo "✓ Indexes added successfully\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
```

---

## 📝 **Implementation Checklist**

### Backend:
- [ ] Add `checkAvailability()` method to `BookingOrder` model
- [ ] Add `getBookedDates()` method to `BookingOrder` model
- [ ] Update `PaymentController::createRentalOrder()` with conflict check
- [ ] Update `PaymentController::createPackageBooking()` with conflict check
- [ ] Create `api/check_availability.php` endpoint
- [ ] Add route for availability API in `public/index.php`
- [ ] Add database indexes for performance

### Frontend:
- [ ] Add real-time availability checking in product rental form
- [ ] Add real-time availability checking in package booking form
- [ ] Integrate date picker library (Flatpickr/Pikaday) to disable booked dates
- [ ] Show availability errors clearly to users
- [ ] Disable submit button when dates conflict

### Testing:
- [ ] Test rental booking with conflicting dates
- [ ] Test package booking with conflicting dates
- [ ] Test edge cases (same start date, same end date, overlapping ranges)
- [ ] Test that pending/paid/verified bookings all block dates
- [ ] Test that cancelled bookings don't block dates
- [ ] Test API endpoint responses
- [ ] Performance test with many bookings

---

## 🎯 **Recommended Date Picker Library**

**Flatpickr** - Recommended for better UX:
- Supports disabling specific dates
- Range selection
- Good documentation
- Lightweight

**Installation:**
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
```

**Usage Example:**
```javascript
flatpickr("#rental_start", {
    minDate: "today",
    disable: bookedDates, // Array of dates from API
    onChange: function(selectedDates, dateStr, instance) {
        checkAvailability();
    }
});
```

---

## 🚨 **Important Notes**

1. **Payment Status Consideration:**
   - Block dates for bookings with status: `pending`, `paid`, `verified`
   - Do NOT block for `cancelled` (if you add this status)

2. **Cancellation:**
   - If a booking is cancelled, dates should become available again
   - Consider adding a `cancelled` status to `payment_status` enum

3. **Admin Override:**
   - Admins might need to book dates even if "conflicting" (e.g., for maintenance)
   - Consider adding an admin bypass or separate admin booking flow

4. **Time Zones:**
   - Ensure date comparisons use the same timezone (Asia/Manila per your config)

5. **Performance:**
   - With many bookings, add database indexes
   - Consider caching booked dates for frequently viewed items

---

*This implementation will prevent double-bookings and provide a better user experience!*

