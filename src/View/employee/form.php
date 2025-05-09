<!-- Card for employee form -->
<div class="card">
    <!-- Card header with dynamic title based on create/edit mode -->
    <div class="card-header">
        <h5 class="mb-0"><?= isset($employee) ? 'Edit Employee' : 'Create Employee' ?></h5>
    </div>
    <div class="card-body">
        <!-- Form with dynamic action URL -->
        <form method="post" action="index.php?route=employees<?= isset($employee) ? '&action=edit&id=' . $employee->getId() : '&action=create' ?>">
            <!-- Two-column layout for name fields -->
            <div class="row">
                <!-- First name field (required) -->
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($employee) ? htmlspecialchars($employee->getFirstName()) : '' ?>" required>
                </div>
                
                <!-- Last name field (required) -->
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= isset($employee) ? htmlspecialchars($employee->getLastName()) : '' ?>" required>
                </div>
            </div>
            
            <!-- Company selection dropdown -->
            <div class="mb-3">
                <label for="company_id" class="form-label">Company</label>
                <select class="form-select" id="company_id" name="company_id">
                    <!-- Option for no company -->
                    <option value="">-- Select Company --</option>
                    <!-- Loop through available companies -->
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company->getId() ?>" <?= (isset($employee) && $employee->getCompany() && $employee->getCompany()->getId() === $company->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($company->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Email field (optional) -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($employee) ? htmlspecialchars($employee->getEmail() ?? '') : '' ?>">
            </div>
            
            <!-- Phone field (optional) -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= isset($employee) ? htmlspecialchars($employee->getPhone() ?? '') : '' ?>">
            </div>
            
            <!-- Form buttons: cancel and save -->
            <div class="d-flex justify-content-between">
                <a href="index.php?route=employees" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="save_employee" class="btn btn-primary">Save Employee</button>
            </div>
        </form>
    </div>
</div> 