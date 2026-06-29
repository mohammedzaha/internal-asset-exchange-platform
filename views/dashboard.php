<?php require __DIR__ . '/layouts/header.php'; ?>
<?php require __DIR__ . '/layouts/navbar.php'; ?>

<div class="container-fluid px-4">

<?php 
/** @var string $memberTempPassword  */
/** @var string $role  */
/** @var array $stats  */


?>

<?php if (!empty($companyCode)): ?>
<script>alert('Your company code is: <?= addslashes($companyCode) ?>\nSave it — you will need it to log in.');</script>
<?php endif; ?>
<?php if (!empty($flashMessage)): ?>
<script>alert('<?= addslashes($flashMessage) ?>');</script>
<?php endif; ?>
<?php if (!empty($memberEmail)): ?>
<script>alert('Member created!\nEmail: <?= addslashes($memberEmail) ?>\nTemporary Password: <?= addslashes($memberTempPassword) ?>');</script>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="margin-bottom:4px">Dashboard</h2>
        <p style="color:var(--text-secondary);font-size:0.875rem;margin:0">
            <?= SecurityHelper::sanitize(SessionHelper::get('department')) ?> Department
        </p>
    </div>
    <span class="badge" style="background:var(--accent);padding:6px 14px;font-size:0.75rem">
        <?= SecurityHelper::sanitize($role) ?>
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-number"><?= (int)$stats['availableCount'] ?></div>
            <div class="stat-label">Available Assets</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-number"><?= (int)$stats['transferredCount'] ?></div>
            <div class="stat-label">Transferred</div>
        </div>
    </div>
    <?php if ($role === 'leader'): ?>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-number"><?= number_format($stats['totalSavings'], 0) ?></div>
            <div class="stat-label">Total Savings</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-number"><?= (int)$stats['pendingCount'] ?></div>
            <div class="stat-label">Pending Approvals</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<h4>Top Valued Available Assets</h4>
<ul class="list-group mb-4" style="max-width:500px">
    <?php foreach ($stats['topAssets'] as $a): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><?= SecurityHelper::sanitize($a['name']) ?></span>
            <strong style="color:var(--accent)"><?= number_format($a['value'], 2) ?></strong>
        </li>
    <?php endforeach; ?>
    <?php if (empty($stats['topAssets'])): ?>
        <li class="list-group-item" style="color:var(--text-secondary)">No assets available yet.</li>
    <?php endif; ?>
</ul>

</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>