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
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <!-- Display message when no employees exist -->
                        <tr>
                            <td colspan="7" class="text-center">No employees found.</td>
                        </tr>
                    <?php else: ?>
                        <!-- Loop through each employee and display their data -->
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?= $employee->getId() ?></td>
                                <td><?= htmlspecialchars($employee->getFirstName()) ?></td>
                                <td><?= htmlspecialchars($employee->getLastName()) ?></td>
                                <td>
                                    <?php if ($employee->getCompany()): ?>
                                        <!-- Display company name if employee is associated with a company -->
                                        <?= htmlspecialchars($employee->getCompany()->getName()) ?>
                                    <?php else: ?>
                                        <!-- Display placeholder if no company is associated -->
                                        <span class="text-muted">None</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($employee->getEmail() ?? '') ?></td>
                                <td><?= htmlspecialchars($employee->getPhone() ?? '') ?></td>
                                <td>
                                    <!-- Action buttons for edit and delete -->
                                    <div class="btn-group">
                                        <a href="index.php?route=employees&action=edit&id=<?= $employee->getId() ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="index.php?route=employees&action=delete&id=<?= $employee->getId() ?>" class="btn btn-sm btn-danger">
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