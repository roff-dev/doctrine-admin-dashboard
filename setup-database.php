<?php
/**
 * Manual Database Setup for Railway
 * Run this after deployment to initialize the database
 */

// Wait a moment for database to be ready
sleep(2);

echo "Setting up database...\n";

// Run setup scripts in order
$scripts = [
    'bin/create-schema.php',
    'bin/create-admin-user.php', 
    'bin/load-fixtures.php'
];

foreach ($scripts as $script) {
    echo "Running $script...\n";
    $output = shell_exec("php $script 2>&1");
    echo $output . "\n";
}

echo "Database setup complete!\n";
?>