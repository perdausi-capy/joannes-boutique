<?php
// src/Models/Category.php

class Category extends BaseModel {
    protected $table = 'categories';
    
    /**
     * Find category by slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Get all active categories
     */
    public function getActive() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get product count for a category
     */
    public function getProductCount($categoryId) {
        $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = :category_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Get categories with product counts
     */
    public function getAllWithProductCounts() {
        $sql = "SELECT c.*, 
                COUNT(p.id) as product_count 
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Delete category
     * Overridden to prevent deletion if products exist
     */
    public function delete($id) {
        // Check if category has products
        $productCount = $this->getProductCount($id);
        if ($productCount > 0) {
            return false;
        }
        
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}