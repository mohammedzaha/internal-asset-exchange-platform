<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container-fluid px-4">

<?php
/** @var array $asset */
/** @var bool $canUndo */
?>

<div style="max-width:720px">
    <a href="<?= BASE_URL ?>/assets" style="font-size:0.83rem;color:var(--text-secondary);text-decoration:none">← Back to Assets</a>

    <h2 style="margin-top:1rem"><?= SecurityHelper::sanitize($asset['name']) ?></h2>

    <div class="card mb-4">
        <?php if ($asset['image_path']): ?>
            <img src="<?= BASE_URL ?>/public/<?= SecurityHelper::sanitize($asset['image_path']) ?>" style="max-height:320px;object-fit:cover;width:100%;border-radius:10px 10px 0 0" alt="">
        <?php endif; ?>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-6">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Category</p>
                    <p style="margin:0;font-weight:500"><?= SecurityHelper::sanitize($asset['category']) ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Value</p>
                    <p style="margin:0;font-weight:600;color:var(--accent)"><?= number_format($asset['value'], 2) ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Condition</p>
                    <p style="margin:0;font-weight:500"><?= SecurityHelper::sanitize($asset['condition']) ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Department</p>
                    <p style="margin:0;font-weight:500"><?= SecurityHelper::sanitize($asset['department']) ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Added by</p>
                    <p style="margin:0;font-weight:500"><?= SecurityHelper::sanitize($asset['added_by_name']) ?></p>
                </div>
                <div class="col-sm-6">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Status</p>
                    <p style="margin:0"><span class="status-<?= SecurityHelper::sanitize($asset['status']) ?>"><?= SecurityHelper::sanitize($asset['status']) ?></span></p>
                </div>
                <?php if ($asset['description']): ?>
                <div class="col-12">
                    <p style="margin:0;color:var(--text-secondary);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Description</p>
                    <p style="margin:0"><?= SecurityHelper::sanitize($asset['description']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <?php if ($asset['status'] === 'available'): ?>
            <form method="POST" action="<?= BASE_URL ?>/transfers/request/<?= $asset['id'] ?>" class="d-inline">
                <button type="submit" class="btn btn-success">Request Transfer</button>
            </form>
        <?php endif; ?>
        <?php if ($canUndo): ?>
            <form method="POST" action="<?= BASE_URL ?>/assets/delete/<?= $asset['id'] ?>" onsubmit="return confirm('Are you sure you want to remove this asset?');" class="d-inline">
                <button type="submit" class="btn btn-danger">Remove Asset</button>
            </form>
        <?php endif; ?>
    </div>
</div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

