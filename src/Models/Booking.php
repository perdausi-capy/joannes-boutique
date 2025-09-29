<?php
class Booking extends BaseModel {
	protected $table = 'bookings';

	public function findRecent($limit = 20) {
		$sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
}
