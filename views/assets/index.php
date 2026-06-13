<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php
/** @var array $assets */
/** @var array $filters */
?>

<?php if (!empty($_SESSION['flash_message'])): ?>
<script>alert('<?= addslashes($_SESSION['flash_message']) ?>');</script>
<?php unset($_SESSION['flash_message']); endif; ?>

<h2>Available Assets</h2>
<a href="<?= BASE_URL ?>/assets/create" class="btn btn-success mb-3">Add Asset</a>

<form method="GET" action="<?= BASE_URL ?>/assets" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?= SecurityHelper::sanitize($filters['search']) ?>">
    </div>
    <div class="col-auto">
        <input type="text" name="category" class="form-control" placeholder="Category" value="<?= SecurityHelper::sanitize($filters['category']) ?>">
    </div>
    <div class="col-auto">
        <input type="text" name="department" class="form-control" placeholder="Department" value="<?= SecurityHelper::sanitize($filters['department']) ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-secondary" type="submit">Filter</button>
    </div>
</form>

<div class="row">
<?php foreach ($assets as $asset): ?>
    <div class="col-md-3 mb-3">
        <div class="card">
            <?php if ($asset['image_path']): ?>
                <img src="<?= BASE_URL ?>/public/<?= SecurityHelper::sanitize($asset['image_path']) ?>" class="card-img-top" alt="">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= SecurityHelper::sanitize($asset['name']) ?></h5>
                <p class="card-text">
                    Category: <?= SecurityHelper::sanitize($asset['category']) ?><br>
                    Value: <?= SecurityHelper::sanitize($asset['value']) ?><br>
                    Condition: <?= SecurityHelper::sanitize($asset['condition']) ?><br>
                    Department: <?= SecurityHelper::sanitize($asset['department']) ?>
                </p>
                <a href="<?= BASE_URL ?>/assets/show/<?= $asset['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($assets)): ?>
    <p>No assets found.</p>
<?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

