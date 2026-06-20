<?php require __DIR__ . '/layouts/header.php'; ?>
<?php require __DIR__ . '/layouts/navbar.php'; ?>

<div style="max-width:480px">
<h2>Change Password</h2>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div><?php endif; ?>
<form method="POST" action="<?= BASE_URL ?>/change-password">
    <div class="mb-3"><label>Current Password</label><input type="password" name="current_password" class="form-control" required></div>
    <div class="mb-3"><label>New Password</label><input type="password" name="new_password" class="form-control" required></div>
    <div class="mb-3"><label>Confirm New Password</label><input type="password" name="confirm_password" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Update Password</button>
</form>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>