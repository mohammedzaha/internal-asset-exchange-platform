<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<h2>Add New Asset</h2>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= SecurityHelper::sanitize($error) ?></div><?php endif; ?>

<form id="add_asset_form" method="POST" action="<?= BASE_URL ?>/assets/store" enctype="multipart/form-data">
    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control" required></div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" id="descriptionField" class="form-control" rows="3"></textarea>
        <button type="button" id="generateBtn" class="btn btn-secondary btn-sm mt-2">✨ Generate with AI</button>
        <span id="generateStatus" style="font-size:0.8rem;color:var(--text-secondary);margin-left:8px"></span>
    </div>

    <div class="mb-3"><label>Value</label><input type="number" step="0.01" name="value" class="form-control" required></div>
    <div class="mb-3"><label>Condition</label><input type="text" name="condition" class="form-control"></div>
    <div class="mb-3"><label>Department</label><input type="text" class="form-control" value="<?= SecurityHelper::sanitize(SessionHelper::get('department')) ?>" disabled></div>
    <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
    <button type="submit" id="add_asset_submit_btn" class="btn btn-primary">Add Asset</button>
</form>


<script>
document.getElementById('generateBtn').addEventListener('click', async function() {
    const name = document.querySelector('input[name="name"]').value.trim();
    const category = document.querySelector('input[name="category"]').value.trim();
    const statusEl = document.getElementById('generateStatus');
    const btn = this;

    if (!name) {
        statusEl.textContent = 'Enter an asset name first.';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Generating...';
    statusEl.textContent = '';

    try {
        const response = await fetch('<?= BASE_URL ?>/assets/generate-description', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `name=${encodeURIComponent(name)}&category=${encodeURIComponent(category)}`
        });
        const data = await response.json();

        if (data.description) {
            document.getElementById('descriptionField').value = data.description;
        } else {
            statusEl.textContent = data.error || 'Something went wrong.';
        }
    } catch (err) {
        statusEl.textContent = 'Network error. Try again.';
    } finally {
        btn.disabled = false;
        btn.textContent = '✨ Generate with AI';
    }
});

document.querySelector('#add_asset_form').addEventListener('submit', function(){
    const btn = document.getElementById('add_asset_submit_btn');
    btn.disabled = true;
    btn.innerText = "Processing...";
});

</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

