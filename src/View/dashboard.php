<?php
/**
 * Dashboard View
 * 
 * This is the main dashboard page that displays summary information about
 * companies and employees with links to manage them.
 */

// Set page title and header
$pageTitle = 'Doctrine Dash';
$pageHeader = 'Dashboard';

// Get counts from database for display in dashboard widgets
$companyCount = $entityManager->getRepository(\App\Entity\Company::class)->count([]);
$employeeCount = $entityManager->getRepository(\App\Entity\Employee::class)->count([]);

// Start output buffer to capture the view content
ob_start();
?>
<h1 class="mb-4"><?= $pageHeader ?></h1>
<div class="row">
    <!-- Companies summary card -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Companies</h5>
                <!-- Display total number of companies -->
                <p class="card-text display-4"><?= $companyCount ?></p>
                <!-- Link to manage companies -->
                <a href="index.php?route=companies" class="btn btn-primary">Manage Companies</a>
            </div>
        </div>
    </div>
    <!-- Employees summary card -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Employees</h5>
                <!-- Display total number of employees -->
                <p class="card-text display-4"><?= $employeeCount ?></p>
                <!-- Link to manage employees -->
                <a href="index.php?route=employees" class="btn btn-primary">Manage Employees</a>
            </div>
        </div>
    </div>
</div>

<?php
// Get the content from the output buffer
$content = ob_get_clean();

// Include the layout template with the captured content
include __DIR__ . '/layout.php'; 