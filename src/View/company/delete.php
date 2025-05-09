<!-- Card for delete confirmation -->
<div class="card">
    <!-- Card header with danger styling -->
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Delete Company</h5>
    </div>
    <div class="card-body">
        <!-- Confirmation message with company name -->
        <p>Are you sure you want to delete the company <strong><?= htmlspecialchars($company->getName()) ?></strong>?</p>
        <!-- Warning about the consequences of deletion -->
        <p class="text-danger">This action cannot be undone. All employees associated with this company will have their company reference removed.</p>
        
        <!-- Form for delete action -->
        <form method="post" action="index.php?route=companies&action=delete&id=<?= $company->getId() ?>">
            <div class="d-flex justify-content-between">
                <!-- Cancel button returns to companies list -->
                <a href="index.php?route=companies" class="btn btn-secondary">Cancel</a>
                <!-- Confirm delete button -->
                <button type="submit" name="delete_company" class="btn btn-danger">Delete Company</button>
            </div>
        </form>
    </div>
</div> 