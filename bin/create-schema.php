<?php
/**
 * Database Schema Management Script
 * 
 * This script handles all schema operations:
 * 1. Creates the database if it doesn't exist
 * 2. Creates tables if they don't exist
 * 3. Updates existing tables if entity definitions have changed
 * 
 * This is the only schema script you need to run when setting up the application
 * or after making changes to entity definitions.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Import required Doctrine classes
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;

// Import entity classes
use App\Entity\User;
use App\Entity\Company;
use App\Entity\Employee;

// Check for command line options
$forceMode = in_array('--force', $_SERVER['argv'] ?? []);

// Get database parameters from doctrine.php
$connectionParams = require_once __DIR__ . '/../config/doctrine.php';
$entityManager = $connectionParams; // The doctrine.php file returns the EntityManager
$dbParams = $entityManager->getConnection()->getParams();

try {
    // Step 1: Create a temporary connection without specifying a database
    $tmpParams = $dbParams;
    unset($tmpParams['dbname']); // Remove database name to connect to server only
    $tmpConnection = DriverManager::getConnection($tmpParams);
    
    // Step 2: Create the database if it doesn't exist
    $databaseName = $dbParams['dbname'];
    $tmpConnection->executeStatement(
        "CREATE DATABASE IF NOT EXISTS `$databaseName`"
    );
    echo "Database '$databaseName' created or already exists.\n";
    
    // Close temporary connection
    $tmpConnection->close();
    
    // Step 4: Create schema tool and get entity metadata
    $tool = new SchemaTool($entityManager);
    $classes = [
        $entityManager->getClassMetadata(User::class),
        $entityManager->getClassMetadata(Company::class),
        $entityManager->getClassMetadata(Employee::class)
    ];
    
    // Step 5: Handle schema creation/update based on current state
    if ($forceMode) {
        // Force mode: Drop and recreate all tables
        echo "Force mode enabled. Dropping and recreating all tables...\n";
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
        echo "Database schema recreated successfully.\n";
    } else {
        // First check if we need to update an existing schema
        $updateSql = $tool->getUpdateSchemaSql($classes, true);
        
        if (empty($updateSql)) {
            // If no updates needed, try creating the schema (will be skipped if tables exist)
            try {
                $tool->createSchema($classes);
                echo "Created database schema successfully.\n";
            } catch (\Exception $e) {
                // If schema exists and no updates needed, we're done
                echo "Database schema is up to date.\n";
            }
        } else {
            // Update existing schema
            echo "Updating database schema...\n";
            foreach ($updateSql as $sql) {
                echo "Executing: $sql\n";
            }
            $tool->updateSchema($classes, true);
            echo "Database schema updated successfully.\n";
        }
    }
    
    echo "\nSchema setup complete! Next steps:\n";
    echo "1. Create admin user: php bin/create-admin-user.php\n";
    echo "2. Start server:     php -S localhost:8000 -t public\n";
    
} catch (\Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
}