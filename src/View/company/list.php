<!-- Header section with page title and add button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Companies</h2>
    <!-- Button to add a new company -->
    <a href="index.php?route=companies&action=create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Add Company
    </a>
</div>

<!-- Hybrid Search Form -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-search"></i> Search Companies</h5>
    </div>
    <div class="card-body">
        <!-- Quick Search Form -->
        <form method="GET" action="index.php" id="quickSearchForm">
            <input type="hidden" name="route" value="companies">
            
            <div class="row g-3">
                <div class="col-12">
                    <label for="quick_search" class="form-label">Quick Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="quick_search" name="search" 
                               value="<?= htmlspecialchars($quickSearch ?? '') ?>" 
                               placeholder="Search by name, email, or website...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <div class="form-text">Search across all company fields at once</div>
                </div>
                
                <div class="col-12">
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false">
                        <i class="bi bi-gear"></i> Advanced Search
                    </button>
                    <a href="index.php?route=companies" class="btn btn-outline-secondary btn-sm ms-2">
                        <i class="bi bi-x-circle"></i> Clear All
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Advanced Search (Collapsible) -->
        <div class="collapse mt-3" id="advancedSearch">
            <hr>
            <form method="GET" action="index.php" id="advancedSearchForm">
                <input type="hidden" name="route" value="companies">
                
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="text-muted"><i class="bi bi-sliders"></i> Advanced Search Options</h6>
                        <p class="small text-muted">Use specific fields for more precise results</p>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="search_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="search_name" name="search_name" 
                               value="<?= htmlspecialchars($searchName ?? '') ?>" 
                               placeholder="e.g., Company 4">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="search_email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="search_email" name="search_email" 
                               value="<?= htmlspecialchars($searchEmail ?? '') ?>" 
                               placeholder="e.g., @example.com">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="search_website" class="form-label">Website</label>
                        <input type="text" class="form-control" id="search_website" name="search_website" 
                               value="<?= htmlspecialchars($searchWebsite ?? '') ?>" 
                               placeholder="e.g., .com">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Advanced Search
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearAdvancedSearch()">
                                <i class="bi bi-x-circle"></i> Clear Advanced
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for search behavior -->
<script>
function clearAdvancedSearch() {
    document.getElementById('search_name').value = '';
    document.getElementById('search_email').value = '';
    document.getElementById('search_website').value = '';
}

// Auto-expand advanced search if advanced fields have values
document.addEventListener('DOMContentLoaded', function() {
    const hasAdvancedValues = <?= (!empty($searchName) || !empty($searchEmail) || !empty($searchWebsite)) ? 'true' : 'false' ?>;
    if (hasAdvancedValues) {
        const advancedCollapse = new bootstrap.Collapse(document.getElementById('advancedSearch'), {show: true});
    }
});
</script>

<!-- Search Results Info -->
<?php if (!empty($quickSearch) || !empty($searchName) || !empty($searchEmail) || !empty($searchWebsite)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>Search Results:</strong> Found <?= $totalCompanies ?> companies
        <?php if (!empty($quickSearch)): ?>
            matching "<?= htmlspecialchars($quickSearch) ?>" (quick search)
        <?php else: ?>
            <?php
            $searchTerms = [];
            if (!empty($searchName)) $searchTerms[] = "Name: \"$searchName\"";
            if (!empty($searchEmail)) $searchTerms[] = "Email: \"$searchEmail\"";
            if (!empty($searchWebsite)) $searchTerms[] = "Website: \"$searchWebsite\"";
            if (!empty($searchTerms)) echo " matching " . implode(", ", $searchTerms) . " (advanced search)";
            ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

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
                    <?php
                    // Build query string for pagination links (preserve search parameters)
                    $searchParams = [];
                    if (!empty($quickSearch)) {
                        $searchParams['search'] = $quickSearch;
                    } else {
                        if (!empty($searchName)) $searchParams['search_name'] = $searchName;
                        if (!empty($searchEmail)) $searchParams['search_email'] = $searchEmail;
                        if (!empty($searchWebsite)) $searchParams['search_website'] = $searchWebsite;
                    }
                    $searchQuery = !empty($searchParams) ? '&' . http_build_query($searchParams) : '';
                    ?>
                    
                    <!-- Previous page link -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=companies&page=<?= $page - 1 ?><?= $searchQuery ?>">
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
                                <a class="page-link" href="index.php?route=companies&page=<?= $i ?><?= $searchQuery ?>"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Next page link -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=companies&page=<?= $page + 1 ?><?= $searchQuery ?>">
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