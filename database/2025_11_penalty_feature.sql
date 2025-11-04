-- Late Return Penalty System Migration
-- Date: 2025-11-02
-- Description: Adds penalty calculation fields to booking_orders table

-- Add penalty columns
ALTER TABLE booking_orders
  ADD COLUMN penalty_amount DECIMAL(10,2) DEFAULT 0 COMMENT 'Late return penalty (₱250/day)' AFTER total_amount,
  ADD COLUMN is_penalty_paid TINYINT(1) DEFAULT 0 COMMENT 'Payment status for penalties (0=unpaid, 1=paid)' AFTER penalty_amount;

-- Add indexes for efficient penalty queries
CREATE INDEX idx_rental_end ON booking_orders(rental_end);
CREATE INDEX idx_penalty_status ON booking_orders(is_penalty_paid, penalty_amount);

-- Update existing records: calculate penalties for overdue rentals
UPDATE booking_orders 
SET penalty_amount = GREATEST(0, DATEDIFF(CURDATE(), rental_end) * 250),
    is_penalty_paid = CASE 
        WHEN payment_status = 'paid' AND CURDATE() > rental_end THEN 0 
        ELSE is_penalty_paid 
    END
WHERE order_type = 'rental' 
  AND rental_end IS NOT NULL 
  AND CURDATE() > rental_end
  AND payment_status IN ('pending', 'paid', 'verified')
  AND (payment_status != 'paid' OR is_penalty_paid = 0);

