<?php require __DIR__ . '/../layouts/header.php'; ?>
<h2>Login</h2>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div><?php endif; ?>
<form method="POST" action="<?= BASE_URL ?>/login">
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
    <div class="mb-3"><label>Company Code</label><input type="text" name="company_code" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<p class="mt-3">New company? <a href="<?= BASE_URL ?>/create-company">Create one</a></p>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
