<?php

require_once __DIR__ . '/../Models/BookingOrder.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Package.php';
require_once __DIR__ . '/../Utils/Auth.php';

class PaymentController
{
    private $bookingOrderModel;
    private $productModel;
    private $packageModel;
    
    public function __construct()
    {
        $this->bookingOrderModel = new BookingOrder();
        $this->productModel = new Product();
        $this->packageModel = new Package();
    }
    
    public function createRentalOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'products');
            exit;
        }

        // Require authentication for renting
        if (!Auth::isLoggedIn()) {
            $_SESSION['error'] = 'Please login to rent items.';
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        $productId = (int)($_POST['product_id'] ?? 0);
        $rentalStart = trim($_POST['rental_start'] ?? '');
        $rentalEnd = trim($_POST['rental_end'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 1);
        $size = trim($_POST['size'] ?? '');
        $contactName = trim($_POST['contact_name'] ?? '');
        $contactEmail = trim($_POST['contact_email'] ?? '');
        $contactPhone = trim($_POST['contact_phone'] ?? '');
        
        // Validate required fields
        if (empty($rentalStart) || empty($rentalEnd)) {
            error_log("Booking validation failed: Missing rental dates. Product ID: {$productId}");
            $_SESSION['error'] = 'Please select both start and end dates for your rental.';
            header('Location: ' . BASE_URL . 'products/show/' . $productId);
            exit;
        }
        
        // Validate date format
        $startTimestamp = strtotime($rentalStart);
        $endTimestamp = strtotime($rentalEnd);
        
        if ($startTimestamp === false || $endTimestamp === false) {
            error_log("Booking validation failed: Invalid date format. Start: {$rentalStart}, End: {$rentalEnd}");
            $_SESSION['error'] = 'Invalid date format. Please select valid dates.';
            header('Location: ' . BASE_URL . 'products/show/' . $productId);
            exit;
        }
        
        // Get product details
        $product = $this->productModel->findById($productId);
        if (!$product) {
            error_log("Booking validation failed: Product not found. Product ID: {$productId}");
            $_SESSION['error'] = 'Product not found';
            header('Location: ' . BASE_URL . 'products');
            exit;
        }
        
        // Validate date range logic
        if ($startTimestamp > $endTimestamp) {
            error_log("Booking validation failed: Start date after end date. Start: {$rentalStart}, End: {$rentalEnd}");
            $_SESSION['error'] = 'Start date must be before end date.';
            header('Location: ' . BASE_URL . 'products/show/' . $productId);
            exit;
        }
        
        // Check availability before creating order
        // IMPORTANT: This validates for the SPECIFIC item_id to prevent double-booking
        try {
            error_log("Validating rental availability: item_id={$productId}, type=rental, start={$rentalStart}, end={$rentalEnd}");
            
            $availability = $this->bookingOrderModel->checkAvailability(
                $productId,  // Specific product ID
                'rental',
                $rentalStart,
                $rentalEnd
            );
            
            if (!$availability['available']) {
                $conflictMsg = 'Selected dates are not available for this item.';
                if (!empty($availability['conflicting_orders'])) {
                    $conflictInfo = $availability['conflicting_orders'][0];
                    if (isset($conflictInfo['rental_start']) && isset($conflictInfo['rental_end'])) {
                        $conflictMsg .= ' Conflicting booking: ' . date('M d', strtotime($conflictInfo['rental_start'])) . 
                                       ' - ' . date('M d, Y', strtotime($conflictInfo['rental_end']));
                    }
                    error_log("Conflict found for item_id={$productId}: Existing booking order_id={$conflictInfo['order_id']}, dates={$conflictInfo['rental_start']} to {$conflictInfo['rental_end']}");
                }
                error_log("Booking validation failed: Date conflict. Product ID: {$productId}, Start: {$rentalStart}, End: {$rentalEnd}");
                $_SESSION['error'] = $conflictMsg;
                header('Location: ' . BASE_URL . 'products/show/' . $productId);
                exit;
            }
            
            error_log("Rental availability confirmed: item_id={$productId} is available for dates {$rentalStart} to {$rentalEnd}");
        } catch (Exception $e) {
            error_log("Booking availability check exception: " . $e->getMessage() . " | Product ID: {$productId} | Start: {$rentalStart} | End: {$rentalEnd}");
            $_SESSION['error'] = 'An error occurred while checking availability. Please try again.';
            header('Location: ' . BASE_URL . 'products/show/' . $productId);
            exit;
        }
        
        // Calculate total amount
        $price = isset($product['price']) ? (float)$product['price'] : 0.0;
        $totalAmount = $price * max(1, (int)$quantity);
        
        // Create booking order
        $orderData = [
            'user_id' => Auth::isLoggedIn() ? $_SESSION['user_id'] : null,
            'order_type' => 'rental',
            'item_id' => $productId,
            'rental_start' => $rentalStart,
            'rental_end' => $rentalEnd,
            'total_amount' => $totalAmount,
            'contact_name' => $contactName,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'quantity' => $quantity,
            'size' => $size
        ];
        
        try {
            $orderId = $this->bookingOrderModel->create($orderData);
            
            if ($orderId) {
                error_log("Rental order created successfully. Order ID: {$orderId}, Product ID: {$productId}");
                // Redirect to product page for unified payment flow
                header('Location: ' . BASE_URL . 'payment?order_id=' . $orderId);
                exit;
            } else {
                error_log("Failed to create rental order. Product ID: {$productId}");
                $_SESSION['error'] = 'Failed to create rental order. Please try again.';
                header('Location: ' . BASE_URL . 'products/show/' . $productId);
                exit;
            }
        } catch (Exception $e) {
            error_log("Exception creating rental order: " . $e->getMessage() . " | Product ID: {$productId}");
            $_SESSION['error'] = 'An error occurred while creating your booking. Please try again.';
            header('Location: ' . BASE_URL . 'products/show/' . $productId);
            exit;
        }
    }
    
    public function createPackageBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'packages');
            exit;
        }

        // Require authentication for booking packages
        if (!Auth::isLoggedIn()) {
            $_SESSION['error'] = 'Please login to book packages.';
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        $packageId = (int)($_POST['package_id'] ?? 0);
        $eventDate = trim($_POST['event_date'] ?? '');
        $numberOfGuests = (int)($_POST['number_of_guests'] ?? 0);
        $contactName = trim($_POST['contact_name'] ?? '');
        $contactEmail = trim($_POST['contact_email'] ?? '');
        $contactPhone = trim($_POST['contact_phone'] ?? '');
        
        // Validate required fields
        if (empty($eventDate)) {
            error_log("Package booking validation failed: Missing event date. Package ID: {$packageId}");
            $_SESSION['error'] = 'Please select an event date.';
            header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
            exit;
        }
        
        // Validate date format
        $eventTimestamp = strtotime($eventDate);
        if ($eventTimestamp === false) {
            error_log("Package booking validation failed: Invalid date format. Event Date: {$eventDate}");
            $_SESSION['error'] = 'Invalid date format. Please select a valid date.';
            header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
            exit;
        }
        
        // Get package details
        $package = $this->packageModel->findById($packageId);
        if (!$package) {
            error_log("Package booking validation failed: Package not found. Package ID: {$packageId}");
            $_SESSION['error'] = 'Package not found';
            header('Location: ' . BASE_URL . 'packages');
            exit;
        }
        
        // Check availability before creating order
        // IMPORTANT: This validates for the SPECIFIC package_id to prevent double-booking
        try {
            error_log("Validating package availability: item_id={$packageId}, type=package, event_date={$eventDate}");
            
            $availability = $this->bookingOrderModel->checkAvailability(
                $packageId,  // Specific package ID
                'package',
                $eventDate
            );
            
            if (!$availability['available']) {
                $formattedDate = date('F j, Y', strtotime($eventDate));
                
                if (!empty($availability['conflicting_orders'])) {
                    $conflictInfo = $availability['conflicting_orders'][0];
                    error_log("Conflict found for item_id={$packageId}: Existing booking order_id={$conflictInfo['order_id']}, event_date={$conflictInfo['event_date']}");
                }
                
                error_log("Package booking validation failed: Date conflict. Package ID: {$packageId}, Event Date: {$eventDate}");
                $_SESSION['error'] = 'This package is already booked for ' . htmlspecialchars($formattedDate) . '. Please choose a different date.';
                header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
                exit;
            }
            
            error_log("Package availability confirmed: item_id={$packageId} is available for event_date={$eventDate}");
        } catch (Exception $e) {
            error_log("Package availability check exception: " . $e->getMessage() . " | Package ID: {$packageId} | Event Date: {$eventDate}");
            $_SESSION['error'] = 'An error occurred while checking availability. Please try again.';
            header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
            exit;
        }
        
        // Calculate total amount
        $totalAmount = $package['price'] ?? 0;
        
        // Create booking order
        $orderData = [
            'user_id' => Auth::isLoggedIn() ? $_SESSION['user_id'] : null,
            'order_type' => 'package',
            'item_id' => $packageId,
            'event_date' => $eventDate,
            'total_amount' => $totalAmount,
            'contact_name' => $contactName,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'quantity' => $numberOfGuests
        ];
        
        try {
            $orderId = $this->bookingOrderModel->create($orderData);
            
            if ($orderId) {
                error_log("Package booking created successfully. Order ID: {$orderId}, Package ID: {$packageId}");
                header('Location: ' . BASE_URL . 'payment?order_id=' . $orderId);
                exit;
            } else {
                error_log("Failed to create package booking. Package ID: {$packageId}");
                $_SESSION['error'] = 'Failed to create package booking. Please try again.';
                header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
                exit;
            }
        } catch (Exception $e) {
            error_log("Exception creating package booking: " . $e->getMessage() . " | Package ID: {$packageId}");
            $_SESSION['error'] = 'An error occurred while creating your booking. Please try again.';
            header('Location: ' . BASE_URL . 'packages/show/' . $packageId);
            exit;
        }
    }
    
    public function showPayment()
    {
        $orderId = (int)($_GET['order_id'] ?? 0);
        
        if (!$orderId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $order = $this->bookingOrderModel->getOrderWithDetails($orderId);
        
        if (!$order) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->render('payment', ['order' => $order]);
    }
    
    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $orderId = (int)($_POST['order_id'] ?? 0);
        $referenceNumber = $_POST['reference_number'] ?? '';
        
        if (!$orderId || !$referenceNumber) {
            $_SESSION['error'] = 'Missing required payment information';
            header('Location: ' . BASE_URL . 'payment?order_id=' . $orderId);
            exit;
        }
        
        // Handle file upload
        $proofImage = null;
        if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH . 'payments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = pathinfo($_FILES['proof_image']['name'], PATHINFO_EXTENSION);
            $fileName = 'payment_' . $orderId . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['proof_image']['tmp_name'], $filePath)) {
                $proofImage = $fileName;
            }
        }
        
        // Get order details to check if penalty should be included
        $order = $this->bookingOrderModel->findByOrderId($orderId);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            header('Location: ' . BASE_URL . 'products');
            exit;
        }
        
        // Check if payment should include penalty
        $includePenalty = isset($_POST['include_penalty']) && $_POST['include_penalty'] == '1';
        $penaltyInfo = $this->bookingOrderModel->calculatePenalty($orderId);
        
        // If there's an overdue penalty and user wants to pay it, include it
        if ($penaltyInfo['is_overdue'] && $penaltyInfo['penalty_amount'] > 0) {
            $includePenalty = true;
        }
        
        // Update order with payment information (including penalty if applicable)
        if ($includePenalty && $penaltyInfo['penalty_amount'] > 0) {
            $success = $this->bookingOrderModel->updatePaymentWithPenalty($orderId, $referenceNumber, $proofImage, true);
        } else {
            $success = $this->bookingOrderModel->updatePayment($orderId, $referenceNumber, $proofImage);
        }
        
        if ($success) {
            // Redirect to product page with success message, or confirmation page
            $itemId = $order['item_id'] ?? 0;
            if ($order['order_type'] === 'rental' && $itemId > 0) {
                $_SESSION['success'] = 'Payment submitted successfully! Your booking is now confirmed.';
                header('Location: ' . BASE_URL . 'products/show/' . $itemId . '?order_id=' . $orderId . '&paid=1');
            } else {
                header('Location: ' . BASE_URL . 'payment-confirmation?order_id=' . $orderId);
            }
            exit;
        } else {
            $_SESSION['error'] = 'Failed to process payment';
            $itemId = $order['item_id'] ?? 0;
            if ($order['order_type'] === 'rental' && $itemId > 0) {
                header('Location: ' . BASE_URL . 'products/show/' . $itemId . '?order_id=' . $orderId);
            } else {
                header('Location: ' . BASE_URL . 'payment?order_id=' . $orderId);
            }
            exit;
        }
    }
    
    public function showConfirmation()
    {
        $orderId = (int)($_GET['order_id'] ?? 0);
        
        if (!$orderId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $order = $this->bookingOrderModel->getOrderWithDetails($orderId);
        
        if (!$order) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->render('payment-confirmation', ['order' => $order]);
    }
    
    private function render($view, $data = [])
    {
        require_once __DIR__ . '/../../config/config.php';
        extract($data);
        include __DIR__ . '/../Views/' . $view . '.php';
    }
}
