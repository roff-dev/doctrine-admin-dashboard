<?php
/**
 * Load Database Fixtures
 * 
 * This script loads the data fixtures defined in src/DataFixtures
 * to populate the database with sample data.
 */

// Include the Autoloader to load all necessary classes
require_once __DIR__ . '/../vendor/autoload.php';

// Get the entity manager from the configuration
$entityManager = require_once __DIR__ . '/../config/doctrine.php';

// Import the fixtures class
use App\DataFixtures\AppFixtures;

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