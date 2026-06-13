<?php require __DIR__ . '/../layouts/header.php'; ?>
<h2>Create New Company</h2>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div><?php endif; ?>
<form method="POST" action="<?= BASE_URL ?>/create-company">
    <div class="mb-3"><label>Company Name</label><input type="text" name="company_name" class="form-control" required></div>
    <div class="mb-3"><label>Your Name</label><input type="text" name="user_name" class="form-control" required></div>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
    <div class="mb-3"><label>Department</label><input type="text" name="department" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Create Company</button>
</form>
<p class="mt-3">Already have an account? <a href="<?= BASE_URL ?>/login">Log in</a></p>
<?php require __DIR__ . '/../layouts/footer.php'; ?>


