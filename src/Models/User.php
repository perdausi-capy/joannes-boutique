<?php
class User extends BaseModel {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function register($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'phone' => $data['phone'] ?? null
        ];
        
        return $this->create($userData);
    }
    
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function ensureSuspendedColumn() {
        // Add is_suspended column if it doesn't exist
        $result = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE 'is_suspended'");
        $exists = $result && $result->fetch();
        if (!$exists) {
            $this->db->exec("ALTER TABLE {$this->table} ADD COLUMN is_suspended TINYINT(1) NOT NULL DEFAULT 0 AFTER role");
        }
    }

    public function suspend(int $userId, bool $suspend): bool {
        $this->ensureSuspendedColumn();
        return $this->update($userId, ['is_suspended' => $suspend ? 1 : 0]);
    }
}