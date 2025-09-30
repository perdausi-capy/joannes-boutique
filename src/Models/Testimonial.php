<?php
class Testimonial extends BaseModel {
    protected $table = 'testimonials';
    
    public function findPublic() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'approved' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function approve($id) {
        return $this->update($id, ['status' => 'approved', 'updated_at' => date('Y-m-d H:i:s')]);
    }

    public function reject($id) {
        return $this->update($id, ['status' => 'rejected', 'updated_at' => date('Y-m-d H:i:s')]);
    }
    
    public function findRecent($limit = 10) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}