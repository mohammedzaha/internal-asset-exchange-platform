<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php
/** @var array $asset */
/** @var bool $canUndo */
?>

<h2><?= SecurityHelper::sanitize($asset['name']) ?></h2>

<?php if ($asset['image_path']): ?>
    <img src="<?= BASE_URL ?>/public/<?= SecurityHelper::sanitize($asset['image_path']) ?>" style="max-width:300px" class="mb-3"><br>
<?php endif; ?>

<p><strong>Category:</strong> <?= SecurityHelper::sanitize($asset['category']) ?></p>
<p><strong>Description:</strong> <?= SecurityHelper::sanitize($asset['description']) ?></p>
<p><strong>Value:</strong> <?= SecurityHelper::sanitize($asset['value']) ?></p>
<p><strong>Condition:</strong> <?= SecurityHelper::sanitize($asset['condition']) ?></p>
<p><strong>Department:</strong> <?= SecurityHelper::sanitize($asset['department']) ?></p>
<p><strong>Added by:</strong> <?= SecurityHelper::sanitize($asset['added_by_name']) ?></p>
<p><strong>Status:</strong> <?= SecurityHelper::sanitize($asset['status']) ?></p>
<p><strong>Created:</strong> <?= SecurityHelper::sanitize($asset['created_at']) ?></p>

<?php if ($asset['status'] === 'available'): ?>
    <button class="btn btn-success">Request Transfer</button> 
<?php endif; ?>

<?php if ($canUndo): ?>
    <form method="POST" action="<?= BASE_URL ?>/assets/delete/<?= $asset['id'] ?>" onsubmit="return confirm('Are you sure you want to delete this asset?');" class="d-inline">
        <button type="submit" class="btn btn-danger">Undo / Delete</button>
    </form>
<?php endif; ?>

<a href="<?= BASE_URL ?>/assets" class="btn btn-secondary">Back to Assets</a>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

