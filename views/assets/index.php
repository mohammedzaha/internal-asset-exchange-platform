<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container-fluid px-4">

<?php
/** @var array $assets */
/** @var array $filters */
?>

<?php if (!empty($_SESSION['flash_message'])): ?>
<script>alert('<?= addslashes($_SESSION['flash_message']) ?>');</script>
<?php unset($_SESSION['flash_message']); endif; ?>

<h2>Available Assets</h2>
<a href="<?= BASE_URL ?>/assets/create" class="btn btn-success mb-3">Add Asset</a>

<div class="mb-3" style="max-width:600px">
    <label>Smart Search</label>
    <div class="d-flex gap-2">
        <input type="text" id="nlSearchInput" class="form-control" placeholder='Try: "cheap chairs in IT department"'>
        <button type="button" id="nlSearchBtn" class="btn btn-primary">Search</button>
    </div>
    <span id="nlSearchStatus" style="font-size:0.8rem;color:var(--text-secondary)"></span>
</div>

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
        <div class="card h-100">
            <?php if ($asset['image_path']): ?>
                <img src="<?= BASE_URL ?>/public/<?= SecurityHelper::sanitize($asset['image_path']) ?>" class="card-img-top" alt="">
            <?php else: ?>
                <div style="height:160px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:0.8rem;">No image</div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= SecurityHelper::sanitize($asset['name']) ?></h5>
                <p class="card-text flex-grow-1">
                    <span style="color:var(--text-secondary)">Category:</span> <?= SecurityHelper::sanitize($asset['category']) ?><br>
                    <span style="color:var(--text-secondary)">Value:</span> <strong><?= number_format($asset['value'], 2) ?></strong><br>
                    <span style="color:var(--text-secondary)">Condition:</span> <?= SecurityHelper::sanitize($asset['condition']) ?><br>
                    <span style="color:var(--text-secondary)">Dept:</span> <?= SecurityHelper::sanitize($asset['department']) ?>
                </p>
                <a href="<?= BASE_URL ?>/assets/show/<?= $asset['id'] ?>" class="btn btn-primary btn-sm mt-2">View Details →</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($assets)): ?>
    <p>No assets found.</p>
<?php endif; ?>
</div>


<script>
document.getElementById('nlSearchBtn').addEventListener('click', async function() {
    const query = document.getElementById('nlSearchInput').value.trim();
    const statusEl = document.getElementById('nlSearchStatus');
    const btn = this;

    if (!query) return;

    btn.disabled = true;
    btn.textContent = 'Searching...';
    statusEl.textContent = '';

    try {
        const response = await fetch('<?= BASE_URL ?>/assets/interpret-query', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `query=${encodeURIComponent(query)}`
        });
        const data = await response.json();

        if (data.error) {
            statusEl.textContent = data.error;
        } else {
            // Redirect to /assets with the AI-extracted filters as normal GET params
            const params = new URLSearchParams();
            if (data.search) params.set('search', data.search);
            if (data.category) params.set('category', data.category);
            if (data.department) params.set('department', data.department);
            window.location.href = '<?= BASE_URL ?>/assets?' + params.toString();
        }
    } catch (err) {
        statusEl.textContent = 'Network error. Try again.';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Search';
    }
});

// Allow Enter key to trigger search
document.getElementById('nlSearchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('nlSearchBtn').click();
    }
});
</script>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

