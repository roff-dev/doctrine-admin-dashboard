<!-- command to create file structure mkdir -p src/Entity config bin -->

<?php
/**
 * Doctrine Configuration File
 * 
 * This file configures the Doctrine ORM connection and returns the EntityManager.
 * It also exports the $connectionParams for use by other scripts.
 */

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

// Create a simple "default" Doctrine ORM configuration for Attributes
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . '/../src'],  // paths to where your entities are located
    true,                  // dev mode (enable caching for production)
);

// Database configuration parameters
$connectionParams = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'doctrine_admin',  // Updated database name
    'host'     => 'localhost',
    'charset'  => 'utf8mb4',         // Added charset for better Unicode support
];

// Create a connection to the database
$connection = DriverManager::getConnection($connectionParams, $config);

// Create the EntityManager
$entityManager = new EntityManager($connection, $config);

return $entityManager;