<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php
/** @var float $totalSavings */
/** @var array $filters */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="margin:0">Transfer History</h2>
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:12px 20px;text-align:center">
        <div style="font-size:1.4rem;font-weight:700;color:var(--success)"><?= number_format($totalSavings, 2) ?></div>
        <div style="font-size:0.75rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px">Total Savings</div>
    </div>
</div>

<form method="GET" action="<?= BASE_URL ?>/transfers/history" class="row g-2 mb-4">
    <div class="col-auto"><input type="text" name="department" class="form-control" placeholder="Filter by department" value="<?= SecurityHelper::sanitize($filters['from_department']) ?>"></div>
    <div class="col-auto"><input type="date" name="date_from" class="form-control" value="<?= SecurityHelper::sanitize($filters['date_from']) ?>"></div>
    <div class="col-auto"><input type="date" name="date_to" class="form-control" value="<?= SecurityHelper::sanitize($filters['date_to']) ?>"></div>
    <div class="col-auto"><button class="btn btn-secondary" type="submit">Filter</button></div>
</form>

<?php if (empty($transfers)): ?>
    <p style="color:var(--text-secondary)">No completed transfers yet.</p>
<?php else: ?>
<div class="card">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Asset</th><th>From</th><th>To</th><th>Requested By</th><th>Approved By</th><th>Date</th><th>Value</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($transfers as $t): ?>
            <tr>
                <td><?= SecurityHelper::sanitize($t['asset_name']) ?></td>
                <td><?= SecurityHelper::sanitize($t['from_department']) ?></td>
                <td><?= SecurityHelper::sanitize($t['to_department']) ?></td>
                <td><?= SecurityHelper::sanitize($t['requester_name']) ?></td>
                <td><?= SecurityHelper::sanitize($t['approver_name']) ?></td>
                <td><?= SecurityHelper::sanitize(date('M j, Y', strtotime($t['approval_date']))) ?></td>
                <td><strong><?= number_format($t['asset_value'], 2) ?></strong></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>