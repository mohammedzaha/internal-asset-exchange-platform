<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<h2>Add New Member</h2>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div><?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/members/store">
    <div class="mb-3"><label>Full Name</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Department</label><input type="text" name="department" class="form-control" required></div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="member">Member</option>
            <option value="leader">Leader</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Add Member</button>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

