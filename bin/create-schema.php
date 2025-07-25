<?php
/**
 * Simplified Database Schema Creation Script for Railway
 * 
 * Creates tables for entities without trying to create the database
 * (Railway already created the database for us)
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Import required Doctrine classes
use Doctrine\ORM\Tools\SchemaTool;

// Import entity classes
use App\Entity\User;
use App\Entity\Company;
use App\Entity\Employee;

try {
    // Get the EntityManager
    $entityManager = require_once __DIR__ . '/../config/doctrine.php';
    
    echo "✅ EntityManager loaded successfully\n";
    
    // Create schema tool and get entity metadata
    $tool = new SchemaTool($entityManager);
    $classes = [
        $entityManager->getClassMetadata(User::class),
        $entityManager->getClassMetadata(Company::class),
        $entityManager->getClassMetadata(Employee::class)
    ];
    
    echo "📋 Entity metadata loaded\n";
    
    // Check if we need to update an existing schema
    $updateSql = $tool->getUpdateSchemaSql($classes, true);
    
    if (empty($updateSql)) {
        // Try creating the schema (will create tables if they don't exist)
        try {
            $tool->createSchema($classes);
            echo "✅ Database schema created successfully\n";
        } catch (\Exception $e) {
            // If schema exists and no updates needed, we're done
            echo "✅ Database schema is up to date\n";
        }
    } else {
        // Update existing schema
        echo "🔄 Updating database schema...\n";
        $tool->updateSchema($classes, true);
        echo "✅ Database schema updated successfully\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Schema creation failed: " . $e->getMessage() . "\n";
    throw $e; // Re-throw to stop execution
}
?>