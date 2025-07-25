<?php
/**
 * Main Entry Point for Admin Dashboard
 * 
 * This file serves as the front controller for the admin dashboard application.
 * It handles authentication, routing, and includes the appropriate controllers or views.
 */

// Include the autoloader to load all necessary classes
require_once __DIR__ . '/../vendor/autoload.php';

// Get the entity manager from the configuration
$entityManager = require_once __DIR__ . '/../config/doctrine.php';

// Start a new session or resume the existing session for user authentication
session_start();

// Define routes - get the route from the URL query parameter or default to 'dashboard'
$route = $_GET['route'] ?? 'dashboard';
// Database setup route for Railway deployment
if ($route === 'setup') {
    echo "<h1>Database Setup</h1>";
    echo "<pre>";
    
    try {
        echo "🔧 Setting up database...\n\n";
        
        // Make EntityManager available to the scripts
        $GLOBALS['entityManager'] = $entityManager;
        
        echo "📄 Creating database schema...\n";
        ob_start();
        include __DIR__ . '/../bin/create-schema.php';
        $output = ob_get_clean();
        echo $output . "\n";
        
        echo "👤 Creating admin user...\n";
        ob_start(); 
        include __DIR__ . '/../bin/create-admin-user.php';
        $output = ob_get_clean();
        echo $output . "\n";
        
        echo "📊 Loading sample data...\n";
        ob_start();
        include __DIR__ . '/../bin/load-fixtures.php';
        $output = ob_get_clean();
        echo $output . "\n";
        
        echo "✅ Database setup completed successfully!\n\n";
        echo "🎉 <strong><a href='index.php'>Access your dashboard now!</a></strong>\n";
        
    } catch (Exception $e) {
        echo "❌ Setup failed: " . $e->getMessage() . "\n";
        echo "Error details: " . $e->getTraceAsString() . "\n";
    }
    
    echo "</pre>";
    exit();
}
// Check if user is authenticated by looking for user_id in the session
$isAuthenticated = isset($_SESSION['user_id']);

// If not authenticated and not on login page, redirect to login
// This ensures that all pages except the login page require authentication
if (!$isAuthenticated && $route !== 'login') {
    header('Location: index.php?route=login');
    exit();
}

// Handle logout - destroy the session and redirect to login page
if ($route === 'logout') {
    session_destroy();
    header('Location: index.php?route=login');
    exit();
}

// Handle login form submission
if ($route === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Look up the user by email in the database
    $userRepository = $entityManager->getRepository(\App\Entity\User::class);
    $user = $userRepository->findOneBy(['email' => $email]);
    
    // Check if user exists first, then verify password
    if (!$user) {
        // User with this email doesn't exist
        $error = 'No account found with this email address';
    } elseif (!password_verify($password, $user->getPassword())) {
        // User exists but password is incorrect
        $error = 'Incorrect password';
    } else {
        // Valid credentials - set session variables and redirect
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        header('Location: index.php');
        exit();
    }
}

// Include appropriate controller or view based on the route
// This acts as a simple router for the application
switch ($route) {
    case 'login':
        include __DIR__ . '/../src/View/login.php';
        break;
    case 'dashboard':
        include __DIR__ . '/../src/View/dashboard.php';
        break;
    case 'companies':
        include __DIR__ . '/../src/Controller/CompanyController.php';
        break;
    case 'employees':
        include __DIR__ . '/../src/Controller/EmployeeController.php';
        break;
    default:
        include __DIR__ . '/../src/View/404.php';
        break;
} 