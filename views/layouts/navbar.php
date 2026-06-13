<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <span class="navbar-brand">Asset Exchange</span>
    <?php if (AuthHelper::isLoggedIn()): ?>
      <div class="d-flex">
        <span class="badge bg-info me-3 align-self-center">
          Role: <?= SecurityHelper::sanitize(SessionHelper::get('role')) ?>
        </span>
        <?php if (SessionHelper::get('role') === 'leader'): ?>
          <a href="<?= BASE_URL ?>/assets/create" class="btn btn-outline-light btn-sm me-2">Add Asset</a>
          <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm me-2">Add Member</a>
          <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm me-2">Pending Approvals</a>
          <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm me-2">All Transfers</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/assets" class="btn btn-outline-light btn-sm me-2">View Assets</a>
          <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm me-2">Add Asset</a>
          <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm me-2">Transfer History</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm me-2">Change Password</a>
        <a href="<?= BASE_URL ?>/logout" class="btn btn-danger btn-sm">Logout</a>
      </div>
    <?php endif; ?>
  </div>
</nav>