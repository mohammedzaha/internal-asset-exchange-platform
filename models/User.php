<?php
class User extends Model {
    public function create(array $data): int {
        $stmt = $this->db->prepare(
            'INSERT INTO users (company_id, name, email, password, role, department) 
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['company_id'],
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'],
            $data['department']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findByEmailAndCompanyCode(string $email, string $companyCode) {
        $stmt = $this->db->prepare(
            'SELECT u.*, c.id AS company_id, c.company_code 
             FROM users u 
             JOIN companies c ON u.company_id = c.id 
             WHERE u.email = ? AND c.company_code = ?'
        );
        $stmt->execute([$email, $companyCode]);
        return $stmt->fetch();
    }

    public function findById(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createMember(array $data): array {
        $tempPassword = bin2hex(random_bytes(4)); // 8-char temp password

            $stmt = $this->db->prepare(
            'INSERT INTO users (company_id, name, email, password, role, department) 
            VALUES (?, ?, ?, ?, ?, ?)'
        );
    $stmt->execute([
        $data['company_id'],
        $data['name'],
        $data['email'],
        password_hash($tempPassword, PASSWORD_DEFAULT),
        $data['role'],
        $data['department']
    ]);

    return ['id' => (int)$this->db->lastInsertId(), 'temp_password' => $tempPassword];
    }

    public function updatePassword(int $id, string $newPassword): void {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hashed, $id]);
    }

    public function findByEmailAndCompany(string $email, int $companyId) {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ? AND company_id = ?');
        $stmt->execute([$email, $companyId]);
        return $stmt->fetch();
    }

    public function getLeaderByCompany(int $companyId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE company_id = ? AND role = 'leader' LIMIT 1");
        $stmt->execute([$companyId]);
        return $stmt->fetch();
    }

}
