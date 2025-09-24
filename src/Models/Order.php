<?php
class Order extends BaseModel {
    protected $table = 'orders';
    
    public function createOrder($userId, $cartItems, $orderData) {
        $this->db->beginTransaction();
        
        try {
            // Calculate total
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            
            // Create order
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
            
            $orderId = $this->create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'total_amount' => $total,
                'shipping_address' => $orderData['shipping_address'],
                'billing_address' => $orderData['billing_address'],
                'payment_method' => $orderData['payment_method'],
                'notes' => $orderData['notes'] ?? ''
            ]);
            
            // Create order items
            $orderItemModel = new OrderItem();
            foreach ($cartItems as $item) {
                $orderItemModel->create([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function getUserOrders($userId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}


