<?php
/**
 * Doctrine Configuration File
 * 
 * Works with Railway's individual MySQL variables
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

// Database configuration - Railway compatible with individual MySQL vars
if (isset($_ENV['MYSQLHOST']) || isset($_SERVER['MYSQLHOST'])) {
    // Railway/Production environment with individual MySQL variables
    $connectionParams = [
        'driver'   => 'pdo_mysql',
        'host'     => $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'],
        'port'     => $_ENV['MYSQLPORT'] ?? $_SERVER['MYSQLPORT'] ?? 3306,
        'user'     => $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'],
        'password' => $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'],
        'dbname'   => $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'],
        'charset'  => 'utf8mb4',
    ];
} elseif (isset($_ENV['DATABASE_URL']) || isset($_SERVER['DATABASE_URL'])) {
    // Fallback to DATABASE_URL if available
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