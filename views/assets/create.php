<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<h2>Add New Asset</h2>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div><?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/assets/store" enctype="multipart/form-data">
    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control" required></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
    <div class="mb-3"><label>Value</label><input type="number" step="0.01" name="value" class="form-control" required></div>
    <div class="mb-3"><label>Condition</label><input type="text" name="condition" class="form-control"></div>
    <div class="mb-3"><label>Department</label><input type="text" class="form-control" value="<?= SecurityHelper::sanitize(SessionHelper::get('department')) ?>" disabled></div>
    <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
    <button type="submit" class="btn btn-primary">Add Asset</button>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

