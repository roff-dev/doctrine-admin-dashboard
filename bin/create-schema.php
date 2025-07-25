<?php
/**
 * Simplified Database Schema Creation Script for Railway
 */

// Import required Doctrine classes
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\User;
use App\Entity\Company;
use App\Entity\Employee;

try {
    // Get EntityManager from global scope (passed from setup route)
    if (isset($GLOBALS['entityManager'])) {
        $entityManager = $GLOBALS['entityManager'];
    } else {
        // Fallback for command line usage
        require_once __DIR__ . '/../vendor/autoload.php';
        $entityManager = require __DIR__ . '/../config/doctrine.php';
    }
    
    echo "✅ EntityManager loaded successfully\n";
    
    // Create schema tool and get entity metadata
    $tool = new SchemaTool($entityManager);
    $classes = [
        $entityManager->getClassMetadata(User::class),
        $entityManager->getClassMetadata(Company::class),
        $entityManager->getClassMetadata(Employee::class)
    ];
    
    echo "📋 Entity metadata loaded\n";
    
    // Try creating the schema
    try {
        $tool->createSchema($classes);
        echo "✅ Database schema created successfully\n";
    } catch (\Exception $e) {
        // If tables already exist, try updating
        echo "🔄 Tables exist, checking for updates...\n";
        $updateSql = $tool->getUpdateSchemaSql($classes, true);
        if (!empty($updateSql)) {
            $tool->updateSchema($classes, true);
            echo "✅ Database schema updated\n";
        } else {
            echo "✅ Database schema is up to date\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Schema creation failed: " . $e->getMessage() . "\n";
    throw $e;
}
?>