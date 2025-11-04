-- Add helpful indexes for faster availability queries
ALTER TABLE booking_orders
  ADD INDEX idx_booking_rental_dates (order_type, item_id, rental_start, rental_end, payment_status),
  ADD INDEX idx_booking_package_date (order_type, item_id, event_date, payment_status);

-- Optional: extend enum to include 'cancelled'
ALTER TABLE booking_orders MODIFY payment_status ENUM('pending','paid','verified','cancelled') DEFAULT 'pending';

