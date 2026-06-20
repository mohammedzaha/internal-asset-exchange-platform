<?php
class Company extends Model {
    public function create(string $name): array {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            $stmt = $this->db->prepare('SELECT id FROM companies WHERE company_code = ?');
            $stmt->execute([$code]);
        } while ($stmt->fetch());

        $stmt = $this->db->prepare('INSERT INTO companies (name, company_code) VALUES (?, ?)');
        $stmt->execute([$name, $code]);

        return ['id' => $this->db->lastInsertId(), 'company_code' => $code];
    }

    public function findByCode(string $code) {
        $stmt = $this->db->prepare('SELECT * FROM companies WHERE company_code = ?');
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function findById(int $id){
        $stmt = $this->db->prepare('SELECT * FROM companies WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}