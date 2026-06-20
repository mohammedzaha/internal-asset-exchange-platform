<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <span class="navbar-brand">⬡ Asset Exchange</span>
    <?php if (AuthHelper::isLoggedIn()): ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="border-color:#334155">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <div class="d-flex flex-wrap ms-auto align-items-center gap-2 mt-2 mt-lg-0">
          <span class="badge">
            <?= SecurityHelper::sanitize(SessionHelper::get('role')) ?>
          </span>
            <a href="<?= BASE_URL ?>/assets" class="btn btn-outline-light btn-sm">View Assets</a>
            <a href="<?= BASE_URL ?>/assets/create" class="btn btn-outline-light btn-sm">Add Asset</a>

          <?php if (SessionHelper::get('role') === 'leader'): ?>
            <a href="<?= BASE_URL ?>/members/create" class="btn btn-outline-light btn-sm">Add Member</a>
            <a href="<?= BASE_URL ?>/transfers/pending" class="btn btn-outline-light btn-sm">Pending Approvals</a>
            <a href="<?= BASE_URL ?>/transfers/history" class="btn btn-outline-light btn-sm">Transfer History</a>
          <?php endif; ?>

          <a href="<?= BASE_URL ?>/change-password" class="btn btn-outline-light btn-sm">Change Password</a>
          <a href="<?= BASE_URL ?>/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</nav>