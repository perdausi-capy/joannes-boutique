<?php
class Cart extends BaseModel {
    protected $table = 'cart';
    
    public function addItem($userId, $sessionId, $productId, $quantity) {
        $existingItem = $this->findCartItem($userId, $sessionId, $productId);
        
        if ($existingItem) {
            return $this->update($existingItem['id'], [
                'quantity' => $existingItem['quantity'] + $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }
    
    public function findCartItem($userId, $sessionId, $productId) {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $params = ['product_id' => $productId];
        
        if ($userId) {
            $sql .= "user_id = :user_id";
            $params['user_id'] = $userId;
        } else {
            $sql .= "session_id = :session_id";
            $params['session_id'] = $sessionId;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getCartItems($userId, $sessionId) {
        $sql = "SELECT c.*, p.name, p.price, p.image 
                FROM {$this->table} c 
                JOIN products p ON c.product_id = p.id 
                WHERE ";
        
        $params = [];
        if ($userId) {
            $sql .= "c.user_id = :user_id";
            $params['user_id'] = $userId;
        } else {
            $sql .= "c.session_id = :session_id";
            $params['session_id'] = $sessionId;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCartCount($userId, $sessionId) {
        $sql = "SELECT SUM(quantity) as count FROM {$this->table} WHERE ";
        
        $params = [];
        if ($userId) {
            $sql .= "user_id = :user_id";
            $params['user_id'] = $userId;
        } else {
            $sql .= "session_id = :session_id";
            $params['session_id'] = $sessionId;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        $result = $stmt->fetch();
        return intval($result['count'] ?? 0);
    }
    
    public function getCartTotal($userId, $sessionId) {
        $sql = "SELECT SUM(c.quantity * p.price) as total 
                FROM {$this->table} c 
                JOIN products p ON c.product_id = p.id 
                WHERE ";
        
        $params = [];
        if ($userId) {
            $sql .= "c.user_id = :user_id";
            $params['user_id'] = $userId;
        } else {
            $sql .= "c.session_id = :session_id";
            $params['session_id'] = $sessionId;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        $result = $stmt->fetch();
        return floatval($result['total'] ?? 0);
    }
    
    public function clearCart($userId, $sessionId) {
        $sql = "DELETE FROM {$this->table} WHERE ";
        
        $params = [];
        if ($userId) {
            $sql .= "user_id = :user_id";
            $params['user_id'] = $userId;
        } else {
            $sql .= "session_id = :session_id";
            $params['session_id'] = $sessionId;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
}


