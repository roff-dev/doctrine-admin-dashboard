<?php
/**
 * Admin User Creation Script
 * 
 * This script creates the default admin user with email admin@admin.com and password "password".
 * It's used for seeding the database with an initial administrator account.
 * The script checks if the admin user already exists before creating it to avoid duplicates.
 */

// Import the User entity class
use App\Entity\User;

// Get EntityManager from global scope (passed from setup route)
if (isset($GLOBALS['entityManager'])) {
    $entityManager = $GLOBALS['entityManager'];
} else {
    // Fallback for command line usage
    require_once __DIR__ . '/../vendor/autoload.php';
    $entityManager = require __DIR__ . '/../config/doctrine.php';
}

// Check if admin user already exists by looking for the email in the database
$userRepository = $entityManager->getRepository(User::class);
$adminUser = $userRepository->findOneBy(['email' => 'admin@admin.com']);

// If admin user already exists, exit the script
if ($adminUser) {
    echo "Admin user already exists.\n";
    exit(0);
}

// Create a new User entity for the admin
$adminUser = new User();
$adminUser->setEmail('admin@admin.com');

// Hash the password 'password' for security
// This ensures the password is not stored in plain text in the database
$hashedPassword = password_hash('password', PASSWORD_DEFAULT);
$adminUser->setPassword($hashedPassword);

try {
    // Persist the user entity to the entity manager
    $entityManager->persist($adminUser);
    
    // Commit the changes to the database
    $entityManager->flush();
    
    echo "Admin user created successfully.\n";
} catch (\Exception $e) {
    // Handle any errors that occur during the database operation
    echo "An error occurred: " . $e->getMessage() . "\n";
} 