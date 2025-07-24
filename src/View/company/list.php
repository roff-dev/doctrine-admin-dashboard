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
                        <th style="width: 60px;">ID</th>
                        <th style="width: 80px;" class="d-none d-md-table-cell">Logo</th>
                        <th>Name</th>
                        <th class="d-none d-lg-table-cell">Email</th>
                        <th class="d-none d-xl-table-cell">Website</th>
                        <th style="width: 120px;">Actions</th>
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
                                <td class="d-none d-md-table-cell">
                                    <?php if ($company->getLogo()): ?>
                                        <!-- Display company logo if available -->
                                        <img src="<?= $company->getLogo() ?>" alt="Logo" style="max-width: 50px; max-height: 50px;">
                                    <?php else: ?>
                                        <!-- Display placeholder text if no logo -->
                                        <span class="text-muted">No logo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($company->getName()) ?></div>
                                    <!-- Show email and website on smaller screens -->
                                    <div class="d-lg-none">
                                        <?php if ($company->getEmail()): ?>
                                            <small class="text-muted d-block"><?= htmlspecialchars($company->getEmail()) ?></small>
                                        <?php endif; ?>
                                        <?php if ($company->getWebsite()): ?>
                                            <small>
                                                <a href="<?= htmlspecialchars($company->getWebsite()) ?>" target="_blank" class="text-decoration-none">
                                                    <?= htmlspecialchars($company->getWebsite()) ?>
                                                </a>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell"><?= htmlspecialchars($company->getEmail() ?? '') ?></td>
                                <td class="d-none d-xl-table-cell">
                                    <?php if ($company->getWebsite()): ?>
                                        <!-- Make website a clickable link -->
                                        <a href="<?= htmlspecialchars($company->getWebsite()) ?>" target="_blank">
                                            <?= htmlspecialchars($company->getWebsite()) ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Action buttons for edit and delete -->
                                    <div class="btn-group" role="group">
                                        <a href="index.php?route=companies&action=edit&id=<?= $company->getId() ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit Company">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-lg-inline ms-1">Edit</span>
                                        </a>
                                        <a href="index.php?route=companies&action=delete&id=<?= $company->getId() ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Delete Company">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-lg-inline ms-1">Delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Companies pagination" class="mt-3">
                <ul class="pagination justify-content-center">
                    <!-- Previous page link -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=companies&page=<?= $page - 1 ?>">
                                <i class="bi bi-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <li class="page-item active">
                                <span class="page-link"><?= $i ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="index.php?route=companies&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Next page link -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=companies&page=<?= $page + 1 ?>">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Pagination info -->
                <div class="text-center text-muted mt-2">
                    Showing <?= min($offset + 1, $totalCompanies) ?> to <?= min($offset + $limit, $totalCompanies) ?> of <?= $totalCompanies ?> companies
                </div>
            </nav>
        <?php endif; ?>
    </div>
</div> 