<!-- Header section with page title and add button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Companies</h2>
    <!-- Button to add a new company -->
    <a href="index.php?route=companies&action=create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Add Company
    </a>
</div>

<!-- Card containing the companies table -->
<div class="card">
    <div class="card-body">
        <!-- Responsive table wrapper -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($companies)): ?>
                        <!-- Display message when no companies exist -->
                        <tr>
                            <td colspan="6" class="text-center">No companies found.</td>
                        </tr>
                    <?php else: ?>
                        <!-- Loop through each company and display its data -->
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td><?= $company->getId() ?></td>
                                <td>
                                    <?php if ($company->getLogo()): ?>
                                        <!-- Display company logo if available -->
                                        <img src="<?= $company->getLogo() ?>" alt="Logo" style="max-width: 50px; max-height: 50px;">
                                    <?php else: ?>
                                        <!-- Display placeholder text if no logo -->
                                        <span class="text-muted">No logo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($company->getName()) ?></td>
                                <td><?= htmlspecialchars($company->getEmail() ?? '') ?></td>
                                <td>
                                    <?php if ($company->getWebsite()): ?>
                                        <!-- Make website a clickable link -->
                                        <a href="<?= htmlspecialchars($company->getWebsite()) ?>" target="_blank">
                                            <?= htmlspecialchars($company->getWebsite()) ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Action buttons for edit and delete -->
                                    <div class="btn-group">
                                        <a href="index.php?route=companies&action=edit&id=<?= $company->getId() ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="index.php?route=companies&action=delete&id=<?= $company->getId() ?>" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 