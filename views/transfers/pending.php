<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php
/** @var array $requests */
?>

<?php if (!empty($_SESSION['flash_message'])): ?>
<script>alert('<?= addslashes($_SESSION['flash_message']) ?>');</script>
<?php unset($_SESSION['flash_message']); endif; ?>

<h2>Pending Transfer Requests</h2>

<?php if (empty($requests)): ?>
    <div style="color:var(--text-secondary);padding:2rem 0">No pending requests at the moment.</div>
<?php endif; ?>

<div class="row">
<?php foreach ($requests as $req): ?>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <?php if ($req['image_path']): ?>
                <img src="<?= BASE_URL ?>/public/<?= SecurityHelper::sanitize($req['image_path']) ?>" class="card-img-top" alt="">
            <?php else: ?>
                <div style="height:120px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:0.8rem;">No image</div>
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= SecurityHelper::sanitize($req['asset_name']) ?></h5>
                <p class="card-text">
                    <span style="color:var(--text-secondary)">From:</span> <?= SecurityHelper::sanitize($req['from_department']) ?><br>
                    <span style="color:var(--text-secondary)">To:</span> <?= SecurityHelper::sanitize($req['to_department']) ?><br>
                    <span style="color:var(--text-secondary)">By:</span> <?= SecurityHelper::sanitize($req['requester_name']) ?><br>
                    <span style="color:var(--text-secondary)">Date:</span> <?= SecurityHelper::sanitize(date('M j, Y', strtotime($req['request_date']))) ?>
                </p>
                <div class="d-flex gap-2 mt-2">
                    <form method="POST" action="<?= BASE_URL ?>/transfers/approve/<?= $req['id'] ?>">
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="POST" action="<?= BASE_URL ?>/transfers/reject/<?= $req['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>