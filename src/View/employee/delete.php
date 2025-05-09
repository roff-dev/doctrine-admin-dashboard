<!-- Card for delete confirmation -->
<div class="card">
    <!-- Card header with danger styling -->
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Delete Employee</h5>
    </div>
    <div class="card-body">
        <!-- Confirmation message with employee's full name -->
        <p>Are you sure you want to delete the employee <strong><?= htmlspecialchars($employee->getFullName()) ?></strong>?</p>
        <!-- Warning about the consequences of deletion -->
        <p class="text-danger">This action cannot be undone.</p>
        
        <!-- Form for delete action -->
        <form method="post" action="index.php?route=employees&action=delete&id=<?= $employee->getId() ?>">
            <div class="d-flex justify-content-between">
                <!-- Cancel button returns to employees list -->
                <a href="index.php?route=employees" class="btn btn-secondary">Cancel</a>
                <!-- Confirm delete button -->
                <button type="submit" name="delete_employee" class="btn btn-danger">Delete Employee</button>
            </div>
        </form>
    </div>
</div> 