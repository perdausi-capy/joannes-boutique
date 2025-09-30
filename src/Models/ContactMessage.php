<?php
class ContactMessage extends BaseModel {
    protected $table = 'contact_messages';
    
    public function findRecent($limit = 50) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function markAsRead($id) {
        return $this->update($id, ['is_read' => 1]);
    }
    
    public function markAsUnread($id) {
        return $this->update($id, ['is_read' => 0]);
    }
}
