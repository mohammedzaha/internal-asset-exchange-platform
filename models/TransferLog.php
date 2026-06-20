<?php
class TransferLog extends Model {

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO transfer_log (company_id, asset_id, requester_id, from_department, to_department, asset_value, status)
             VALUES (?, ?, ?, ?, ?, ?, 'pending')"
        );
        $stmt->execute([
            $data['company_id'],
            $data['asset_id'],
            $data['requester_id'],
            $data['from_department'],
            $data['to_department'],
            $data['asset_value']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function countPendingByCompany(int $companyId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM transfer_log WHERE company_id = ? AND status = 'pending'");
        $stmt->execute([$companyId]);
        return (int)$stmt->fetchColumn();
    }

    public function getPendingByCompany(int $companyId): array {
        $stmt = $this->db->prepare(
            "SELECT t.*, a.name AS asset_name, a.image_path, u.name AS requester_name
             FROM transfer_log t
             JOIN assets a ON t.asset_id = a.id
             JOIN users u ON t.requester_id = u.id
             WHERE t.company_id = ? AND t.status = 'pending'
             ORDER BY t.request_date ASC"
        );
        $stmt->execute([$companyId]);
        return $stmt->fetchAll();
    }

    public function getApprovedByCompany(int $companyId, array $filters = []): array {
        $sql = "SELECT t.*, a.name AS asset_name, a.image_path, 
                       ur.name AS requester_name, ua.name AS approver_name
                FROM transfer_log t
                JOIN assets a ON t.asset_id = a.id
                JOIN users ur ON t.requester_id = ur.id
                LEFT JOIN users ua ON t.approver_id = ua.id
                WHERE t.company_id = ? AND t.status = 'approved'";
        $params = [$companyId];

        if (!empty($filters['from_department'])) {
            $sql .= " AND t.from_department = ?";
            $params[] = $filters['from_department'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND t.approval_date >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND t.approval_date <= ?";
            $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY t.approval_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalSavings(int $companyId): float {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(asset_value),0) FROM transfer_log WHERE company_id = ? AND status = 'approved'"
        );
        $stmt->execute([$companyId]);
        return (float)$stmt->fetchColumn();
    }

    public function findById(int $id, int $companyId) {
        $stmt = $this->db->prepare("SELECT * FROM transfer_log WHERE id = ? AND company_id = ?");
        $stmt->execute([$id, $companyId]);
        return $stmt->fetch();
    }

    public function updateStatus(int $id, string $status, ?int $approverId = null): void {
        if ($status === 'approved') {
            $stmt = $this->db->prepare(
                "UPDATE transfer_log SET status = ?, approver_id = ?, approval_date = NOW() WHERE id = ?"
            );
            $stmt->execute([$status, $approverId, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE transfer_log SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
        }
    }
}

