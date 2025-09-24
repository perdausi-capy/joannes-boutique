<?php
class Testimonial extends BaseModel {
    protected $table = 'testimonials';
    
    public function getApproved($limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'approved' ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}


