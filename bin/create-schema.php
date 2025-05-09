<?php
/**
 * Database Schema Creation Script
 * 
 * This script creates the database if it doesn't exist and then creates all the tables
 * based on the entity classes in the application.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Import required Doctrine classes
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Mapping\ClassMetadata;

// Import entity classes
use App\Entity\User;
use App\Entity\Company;
use App\Entity\Employee;

// Define database parameters directly (same as in doctrine.php)
$dbParams = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'doctrine_admin',
    'host'     => 'localhost',
    'charset'  => 'utf8mb4',
];

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
    
    // Step 3: Create a full connection to the new database
    $config = ORMSetup::createAttributeMetadataConfiguration(
        [__DIR__ . '/../src'],  // paths to where your entities are located
        true                    // dev mode (enable caching for production)
    );
    
    $connection = DriverManager::getConnection($dbParams, $config);
    $entityManager = new EntityManager($connection, $config);
    
    // Step 4: Generate and execute schema - manually specifying entity classes
    $tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
    
    // Create metadata collection manually
    $classes = [
        $entityManager->getClassMetadata(User::class),
        $entityManager->getClassMetadata(Company::class),
        $entityManager->getClassMetadata(Employee::class)
    ];
    
    // Create the schema for these entities
    $tool->createSchema($classes);
    echo "Created database schema successfully.\n";
    
} catch (\Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    echo "Error details: " . $e->getTraceAsString() . "\n";
}