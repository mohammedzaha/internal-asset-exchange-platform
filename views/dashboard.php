<?php require __DIR__ . '/layouts/header.php'; ?>
<?php require __DIR__ . '/layouts/navbar.php'; ?>

<?php
/** @var string $role */
/**@var string $department */
?>

<?php if (!empty($companyCode)): ?>
<script>
    alert('Your company code is: <?= addslashes($companyCode) ?>\nSave it — you will need it to log in.');
</script>
<?php endif; ?>

<?php if (!empty($flashMessage)): ?>
<script>
    alert('<?= addslashes($flashMessage) ?>');
</script>
<?php endif; ?>

<h2>Dashboard</h2>
<p>Welcome! Your role is: <strong><?= SecurityHelper::sanitize($role) ?></strong></p>
<p>Department: <strong><?= SecurityHelper::sanitize($department) ?></strong></p>

<?php require __DIR__ . '/layouts/footer.php'; ?>