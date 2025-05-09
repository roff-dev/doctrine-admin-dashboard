<?php
/**
 * Database Schema Update Script
 * 
 * This script updates the database schema based on changes to entity classes.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Import required Doctrine classes
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;

// Import entity classes
use App\Entity\User;
use App\Entity\Company;
use App\Entity\Employee;

// Define database parameters
$dbParams = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'doctrine_admin',
    'host'     => 'localhost',
    'charset'  => 'utf8mb4',
];

try {
    // Create entity manager
    $config = ORMSetup::createAttributeMetadataConfiguration(
        [__DIR__ . '/../src'],
        true
    );
    
    $connection = DriverManager::getConnection($dbParams, $config);
    $entityManager = new EntityManager($connection, $config);
    
    // Create schema tool
    $tool = new SchemaTool($entityManager);
    
    // Get metadata for our entities
    $classes = [
        $entityManager->getClassMetadata(User::class),
        $entityManager->getClassMetadata(Company::class),
        $entityManager->getClassMetadata(Employee::class)
    ];
    
    echo "Updating database schema...\n";
    
    // Get SQL queries for schema update
    $sqls = $tool->getUpdateSchemaSql($classes, true);
    
    if (count($sqls) === 0) {
        echo "Nothing to update - your database is already in sync with the current entity metadata.\n";
    } else {
        // Execute the queries
        foreach ($sqls as $sql) {
            echo "Executing: $sql\n";
            $connection->executeStatement($sql);
        }
        
        echo "Database schema updated successfully.\n";
    }
    
} catch (\Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
} 