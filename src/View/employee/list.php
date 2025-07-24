<!-- Header section with page title and add button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Employees</h2>
    <!-- Button to add a new employee -->
    <a href="index.php?route=employees&action=create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Add Employee
    </a>
</div>

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
                    <!-- Previous page link -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=employees&page=<?= $page - 1 ?>">
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
                                <a class="page-link" href="index.php?route=employees&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <!-- Next page link -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?route=employees&page=<?= $page + 1 ?>">
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