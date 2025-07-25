<?php
/**
 * Load Database Fixtures
 * 
 * This script loads the data fixtures defined in src/DataFixtures
 * to populate the database with sample data.
 */

// Import the fixtures class
use App\DataFixtures\AppFixtures;

// Get EntityManager from global scope (passed from setup route)
if (isset($GLOBALS['entityManager'])) {
    $entityManager = $GLOBALS['entityManager'];
} else {
    // Fallback for command line usage
    require_once __DIR__ . '/../vendor/autoload.php';
    $entityManager = require __DIR__ . '/../config/doctrine.php';
}

try {
    echo "Loading fixtures...\n";

    // Create and run fixtures
    $fixtures = new AppFixtures();
    $fixtures->load($entityManager);

    echo "Fixtures loaded successfully!\n";

} catch (\Exception $e) {
    echo "An error occured: " . $e->getMessage() . "\n";
    echo "Error details:  " . $e->getTraceAsString() . "\n";
}
?>