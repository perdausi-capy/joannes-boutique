<?php
class ProductImage extends BaseModel {
	protected $table = 'product_images';

	public function findByProductId($productId) {
		$sql = "SELECT * FROM {$this->table} WHERE product_id = :product_id ORDER BY sort_order ASC, id ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
}
