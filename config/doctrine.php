<?php
/**
 * Doctrine Configuration File
 * 
 * Works both locally and on Railway hosting.
 */

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

// Create a simple Doctrine ORM configuration for Attributes
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . '/../src'],  
    true,
);

// Database configuration - Railway compatible
if (isset($_ENV['DATABASE_URL']) || isset($_SERVER['DATABASE_URL'])) {
    // Railway/Production environment
    $databaseUrl = $_ENV['DATABASE_URL'] ?? $_SERVER['DATABASE_URL'];
    $connectionParams = [
        'url' => $databaseUrl,
        'charset' => 'utf8mb4',
    ];
} else {
    // Local development environment
    $connectionParams = [
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => '',
        'dbname'   => 'doctrine_admin',  
        'host'     => 'localhost',
        'charset'  => 'utf8mb4', 
    ];
}

// Create a connection to the database
$connection = DriverManager::getConnection($connectionParams, $config);

// Create the EntityManager
$entityManager = new EntityManager($connection, $config);

return $entityManager;