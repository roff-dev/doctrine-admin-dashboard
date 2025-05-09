<!-- Card for company form -->
<div class="card">
    <!-- Card header with dynamic title based on create/edit mode -->
    <div class="card-header">
        <h5 class="mb-0"><?= isset($company) ? 'Edit Company' : 'Create Company' ?></h5>
    </div>
    <div class="card-body">
        <!-- Form with file upload support (enctype) and dynamic action URL -->
        <form method="post" action="index.php?route=companies<?= isset($company) ? '&action=edit&id=' . $company->getId() : '&action=create' ?>" enctype="multipart/form-data">
            <!-- Company name field (required) -->
            <div class="mb-3">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= isset($company) ? htmlspecialchars($company->getName()) : '' ?>" required>
            </div>
            
            <!-- Company email field (optional) -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($company) ? htmlspecialchars($company->getEmail() ?? '') : '' ?>">
            </div>
            
            <!-- Company website field (optional) -->
            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="url" class="form-control" id="website" name="website" value="<?= isset($company) ? htmlspecialchars($company->getWebsite() ?? '') : '' ?>">
                <div class="form-text">Please include http:// or https://</div>
            </div>
            
            <!-- Company logo field (optional) -->
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <?php if (isset($company) && $company->getLogo()): ?>
                    <!-- Display current logo if available -->
                    <div class="mb-2">
                        <img src="<?= $company->getLogo() ?>" alt="Current Logo" style="max-width: 100px; max-height: 100px;">
                        <p class="form-text">Current logo</p>
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                <div class="form-text">Logo must be at least 100x100 pixels.</div>
            </div>
            
            <!-- Form buttons: cancel and save -->
            <div class="d-flex justify-content-between">
                <a href="index.php?route=companies" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="save_company" class="btn btn-primary">Save Company</button>
            </div>
        </form>
    </div>
</div> 