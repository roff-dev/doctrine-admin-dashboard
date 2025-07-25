<?php
/**
 * Database Connection Test for Railway
 * Access via: your-railway-url/test-db.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

echo "<h1>Database Connection Test</h1>";
echo "<pre>";

// Check environment variables
echo "=== Environment Variables ===\n";
$mysqlHost = $_ENV['MYSQLHOST'] ?? $_SERVER['MYSQLHOST'] ?? 'Not set';
$mysqlUser = $_ENV['MYSQLUSER'] ?? $_SERVER['MYSQLUSER'] ?? 'Not set';
$mysqlDb = $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? 'Not set';

echo "MYSQLHOST: " . ($mysqlHost !== 'Not set' ? "✅ " . $mysqlHost : "❌ Missing") . "\n";
echo "MYSQLUSER: " . ($mysqlUser !== 'Not set' ? "✅ " . $mysqlUser : "❌ Missing") . "\n";
echo "MYSQLDATABASE: " . ($mysqlDb !== 'Not set' ? "✅ " . $mysqlDb : "❌ Missing") . "\n";

echo "\n=== Testing Database Connection ===\n";

try {
    // Try to get entity manager
    $entityManager = require_once __DIR__ . '/../config/doctrine.php';
    echo "✅ EntityManager created successfully\n";
    
    // Try to connect to database
    $connection = $entityManager->getConnection();
    $connection->connect();
    echo "✅ Database connection successful\n";
    
    // Try a simple query
    $result = $connection->executeQuery('SELECT 1 as test');
    $data = $result->fetchAssociative();
    echo "✅ Database query successful: " . json_encode($data) . "\n";
    
    echo "\n🎯 Database is ready! \n";
    echo "<a href='/?route=setup'>Run database setup</a>\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>