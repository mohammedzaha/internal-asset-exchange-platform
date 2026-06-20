<?php
class Asset extends Model {
    public function getAvailableByCompany(int $companyId, array $filters = []): array {
        $sql = "SELECT a.*, u.name AS added_by_name 
                FROM assets a 
                LEFT JOIN users u ON a.added_by = u.id 
                WHERE a.company_id = ? AND a.status = 'available'";
        $params = [$companyId];

        if (!empty($filters['category'])) {
            $sql .= " AND a.category LIKE ?";
            $params[] = '%' . $filters['category'] . '%';
        }
        if (!empty($filters['department'])) {
            $sql .= " AND a.department = ?";
            $params[] = $filters['department'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND a.name LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO assets (company_id, name, category, description, value, `condition`, image_path, status, added_by, department)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'available', ?, ?)"
        );
        $stmt->execute([
            $data['company_id'],
            $data['name'],
            $data['category'],
            $data['description'],
            $data['value'],
            $data['condition'],
            $data['image_path'],
            $data['added_by'],
            $data['department']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id, int $companyId) {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.name AS added_by_name 
             FROM assets a 
             LEFT JOIN users u ON a.added_by = u.id 
             WHERE a.id = ? AND a.company_id = ?"
        );
        $stmt->execute([$id, $companyId]);
        return $stmt->fetch();
    }

    public function delete(int $id, int $companyId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM assets WHERE id = ? AND company_id = ? AND status = 'available'"
        );
        $stmt->execute([$id, $companyId]);
        return $stmt->rowCount() > 0;
    }

    public function updateStatus(int $id, string $status, int $companyId): void {
        $stmt = $this->db->prepare("UPDATE assets SET status = ? WHERE id = ? AND company_id = ?");
        $stmt->execute([$status, $id, $companyId]);
    }

    public function countAvailableByCompany(int $companyId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM assets WHERE company_id = ? AND status = 'available'");
        $stmt->execute([$companyId]);
        return (int)$stmt->fetchColumn();
    }

    public function countTransferredByCompany(int $companyId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM assets WHERE company_id = ? AND status = 'assigned'");
        $stmt->execute([$companyId]);
        return (int)$stmt->fetchColumn();
    }

    public function getTopValuedAvailable(int $companyId, int $limit = 3): array {
        $stmt = $this->db->prepare(
            "SELECT name, value FROM assets WHERE company_id = ? AND status = 'available' ORDER BY value DESC LIMIT ?"
        );
        $stmt->bindValue(1, $companyId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

