<!-- Header section with page title and add button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Employees</h2>
    <!-- Button to add a new employee -->
    <a href="index.php?route=employees&action=create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Add Employee
    </a>
</div>

<!-- Hybrid Search Form -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-search"></i> Search Employees</h5>
    </div>
    <div class="card-body">
        <!-- Quick Search Form -->
        <form method="GET" action="index.php" id="quickSearchForm">
            <input type="hidden" name="route" value="employees">
            
            <div class="row g-3">
                <div class="col-12">
                    <label for="quick_search" class="form-label">Quick Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="quick_search" name="search" 
                               value="<?= htmlspecialchars($quickSearch ?? '') ?>" 
                               placeholder="Search by name, email, phone, or company...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <div class="form-text">Search across all employee fields including company name</div>
                </div>
                
                <div class="col-12">
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false">
                        <i class="bi bi-gear"></i> Advanced Search
                    </button>
                    <a href="index.php?route=employees" class="btn btn-outline-secondary btn-sm ms-2">
                        <i class="bi bi-x-circle"></i> Clear All
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Advanced Search (Collapsible) -->
        <div class="collapse mt-3" id="advancedSearch">
            <hr>
            <form method="GET" action="index.php" id="advancedSearchForm">
                <input type="hidden" name="route" value="employees">
                
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="text-muted"><i class="bi bi-sliders"></i> Advanced Search Options</h6>
                        <p class="small text-muted">Use specific fields for more precise results</p>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="search_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="search_name" name="search_name" 
                               value="<?= htmlspecialchars($searchName ?? '') ?>" 
                               placeholder="e.g., John, Jones">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="search_email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="search_email" name="search_email" 
                               value="<?= htmlspecialchars($searchEmail ?? '') ?>" 
                               placeholder="e.g., @example.com">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="search_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="search_phone" name="search_phone" 
                               value="<?= htmlspecialchars($searchPhone ?? '') ?>" 
                               placeholder="e.g., 07123">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="search_company" class="form-label">Company</label>
                        <input type="text" class="form-control" id="search_company" name="search_company" 
                               value="<?= htmlspecialchars($searchCompany ?? '') ?>" 
                               placeholder="e.g., Company 4">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Advanced Search
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearAdvancedSearchEmployees()">
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
function clearAdvancedSearchEmployees() {
    document.getElementById('search_name').value = '';
    document.getElementById('search_email').value = '';
    document.getElementById('search_phone').value = '';
    document.getElementById('search_company').value = '';
}

// Auto-expand advanced search if advanced fields have values
document.addEventListener('DOMContentLoaded', function() {
    const hasAdvancedValues = <?= (!empty($searchName) || !empty($searchEmail) || !empty($searchPhone) || !empty($searchCompany)) ? 'true' : 'false' ?>;
    if (hasAdvancedValues) {
        const advancedCollapse = new bootstrap.Collapse(document.getElementById('advancedSearch'), {show: true});
    }
});
</script>

<!-- Search Results Info -->
<?php if (!empty($quickSearch) || !empty($searchName) || !empty($searchEmail) || !empty($searchPhone) || !empty($searchCompany)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>Search Results:</strong> Found <?= $totalEmployees ?> employees
        <?php if (!empty($quickSearch)): ?>
            matching "<?= htmlspecialchars($quickSearch) ?>" (quick search)
        <?php else: ?>
            <?php
            $searchTerms = [];
            if (!empty($searchName)) $searchTerms[] = "Name: \"$searchName\"";
            if (!empty($searchEmail)) $searchTerms[] = "Email: \"$searchEmail\"";
            if (!empty($searchPhone)) $searchTerms[] = "Phone: \"$searchPhone\"";
            if (!empty($searchCompany)) $searchTerms[] = "Company: \"$searchCompany\"";
            if (!empty($searchTerms)) echo " matching " . implode(", ", $searchTerms) . " (advanced search)";
            ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Card containing the employees table -->
<div class="card">
    <div class="card-body">
        <!-- Responsive table wrapper -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Company</th>
                        <th class="d-none d-lg-table-cell">Email</th>
                        <th class="d-none d-xl-table-cell">Phone</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <!-- Display message when no employees exist -->
                        <tr>
                            <td colspan="6" class="text-center">No employees found.</td>
                        </tr>
                    <?php else: ?>
                        <!-- Loop through each employee and display their data -->
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?= $employee->getId() ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($employee->getFullName()) ?></div>
                                    <!-- Show additional info on smaller screens -->
                                    <div class="d-md-none">
                                        <?php if ($employee->getCompany()): ?>
                                            <small class="text-muted d-block"><?= htmlspecialchars($employee->getCompany()->getName()) ?></small>
                                        <?php endif; ?>
                                        <?php if ($employee->getEmail()): ?>
                                            <small class="text-muted d-block"><?= htmlspecialchars($employee->getEmail()) ?></small>
                                        <?php endif; ?>
                                        <?php if ($employee->getPhone()): ?>
                                            <small class="text-muted d-block"><?= htmlspecialchars($employee->getPhone()) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?php if ($employee->getCompany()): ?>
                                        <!-- Display company name if employee is associated with a company -->
                                        <?= htmlspecialchars($employee->getCompany()->getName()) ?>
                                    <?php else: ?>
                                        <!-- Display placeholder if no company is associated -->
                                        <span class="text-muted">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="d-none d-lg-table-cell"><?= htmlspecialchars($employee->getEmail() ?? '') ?></td>
                                <td class="d-none d-xl-table-cell"><?= htmlspecialchars($employee->getPhone() ?? '') ?></td>
                                <td>
                                    <!-- Action buttons for edit and delete -->
                                    <div class="btn-group" role="group">
                                        <a href="index.php?route=employees&action=edit&id=<?= $employee->getId() ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit Employee">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-lg-inline ms-1">Edit</span>
                                        </a>
                                        <a href="index.php?route=employees&action=delete&id=<?= $employee->getId() ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Delete Employee">
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
            <nav aria-label="Employees pagination" class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php
                    // Build query string for pagination links (preserve search parameters)
                    $searchParams = [];
                    if (!empty($quickSearch)) {
                        $searchParams['search'] = $quickSearch;
                    } else {
                        if (!empty($searchName)) $searchParams['search_name'] = $searchName;
                        if (!empty($searchEmail)) $searchParams['search_email'] = $searchEmail;
                        if (!empty($searchPhone)) $searchParams['search_phone'] = $searchPhone;
                        if (!empty($searchCompany)) $searchParams['search_company'] = $searchCompany;
                    }
                    $searchQuery = !empty($searchParams) ? '&' . http_build_query($searchParams) : '';
                    ?>
                    
                    <!-- Previous page link -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=employees&page=<?= $page - 1 ?><?= $searchQuery ?>">
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
                                <a class="page-link" href="index.php?route=employees&page=<?= $i ?><?= $searchQuery ?>"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Next page link -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=employees&page=<?= $page + 1 ?><?= $searchQuery ?>">
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
                    Showing <?= min($offset + 1, $totalEmployees) ?> to <?= min($offset + $limit, $totalEmployees) ?> of <?= $totalEmployees ?> employees
                </div>
            </nav>
        <?php endif; ?>
    </div>
</div> 