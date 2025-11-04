<?php
class Package extends BaseModel {
    protected $table = 'packages';
    protected $primaryKey = 'package_id';

    public function createPackage(array $data) {
        $payload = [
            'package_name' => $data['package_name'] ?? null,
            'hotel_name' => $data['hotel_name'] ?? null,
            'hotel_address' => $data['hotel_address'] ?? null,
            'hotel_description' => $data['hotel_description'] ?? null,
            'number_of_guests' => (int)($data['number_of_guests'] ?? 0),
            'inclusions' => json_encode($data['inclusions'] ?? new stdClass()),
            'freebies' => $data['freebies'] ?? null,
            'price' => $data['price'] ?? null,
        ];
        if (!empty($data['background_image'])) {
            $payload['background_image'] = $data['background_image'];
        }
        return $this->create($payload);
    }

    public function findRecent($limit = 50) {
        return $this->findAll($limit);
    }

    public function updatePackage(int $id, array $data) {
        $payload = [
            'package_name' => $data['package_name'] ?? null,
            'hotel_name' => $data['hotel_name'] ?? null,
            'hotel_address' => $data['hotel_address'] ?? null,
            'hotel_description' => $data['hotel_description'] ?? null,
            'number_of_guests' => (int)($data['number_of_guests'] ?? 0),
            'inclusions' => json_encode($data['inclusions'] ?? new stdClass()),
            'freebies' => $data['freebies'] ?? null,
            'price' => $data['price'] ?? null,
        ];
        if (!empty($data['background_image'])) {
            $payload['background_image'] = $data['background_image'];
        }
        return $this->update($id, $payload);
    }

    public function deletePackage(int $id) {
        return $this->delete($id);
    }
}


