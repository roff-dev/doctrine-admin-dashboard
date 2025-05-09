<?php
/**
 * 404 Error Page
 * 
 * This page is displayed when a user tries to access a route that doesn't exist.
 * It provides a friendly error message and a link back to the dashboard.
 */

// Set page title and header
$pageTitle = 'Page Not Found';
$pageHeader = '404 - Page Not Found';

// Start output buffer to capture the view content
ob_start();
?>

<!-- Warning alert with 404 message -->
<div class="alert alert-warning">
    <h4 class="alert-heading">Page Not Found</h4>
    <p>The page you are looking for does not exist.</p>
    <hr>
    <p class="mb-0">
        <!-- Button to return to dashboard -->
        <a href="index.php" class="btn btn-primary">Return to Dashboard</a>
    </p>
</div>

<?php
// Get the content from the output buffer
$content = ob_get_clean();

// Include the layout template with the captured content
include __DIR__ . '/layout.php'; 