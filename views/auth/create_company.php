<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Company — Asset Exchange</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Create your workspace</h2>
        <p class="auth-subtitle">Set up your company and become Team Leader</p>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/create-company">
            <div class="mb-3"><label>Company Name</label><input type="text" name="company_name" class="form-control" placeholder="Acme Corp" required></div>
            <div class="mb-3"><label>Your Name</label><input type="text" name="user_name" class="form-control" placeholder="John Doe" required></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" placeholder="you@company.com" required></div>
            <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" placeholder="••••••••" required></div>
            <div class="mb-3"><label>Department</label><input type="text" name="department" class="form-control" placeholder="e.g. IT, HR, Finance" required></div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Create Workspace</button>
        </form>
        <p class="auth-footer">Already have an account? <a href="<?= BASE_URL ?>/login">Sign in</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>