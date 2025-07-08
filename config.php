<?php
// config.php - Application Configuration

/**
 * Database Settings (PDO)
 *
 * It's crucial to keep this file secure. In a production environment,
 * consider placing it outside the web-accessible directory.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'money_mate_db'); // Your actual database name
define('DB_USER', 'root');         // Your actual database username
define('DB_PASS', 'danny3');       // Your actual database password

// Data Source Name (DSN) for PDO connection
define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4');

/**
 * Application Settings
 *
 * Define various constants for your application.
 */
define('SITE_NAME', 'Money Mate');
define('BASE_URL', 'http://localhost/MoneyMate/group_assignment_CSC578/'); // Adjust this to your project's base URL

// Define paths to important directories (relative to your project root)
define('INC_PATH', __DIR__ . '/includes/'); // Path to your includes directory
define('VIEW_PATH', __DIR__ . '/views/');   // Path to your view/template files

/**
 * Error Reporting Settings
 *
 * Configure how PHP errors are displayed/logged.
 * Change for production environment!
 */
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    // Development Environment Settings
    ini_set('display_errors', 1);        // <--- Ensure this is 1
    ini_set('display_startup_errors', 1); // <--- Ensure this is 1
    error_reporting(E_ALL);              // <--- Ensure this is E_ALL
} else {
    // Production Environment Settings
    ini_set('display_errors', 0); // NEVER display errors in production!
    ini_set('log_errors', 1);     // ALWAYS log errors in production!
    ini_set('error_log', __DIR__ . '/logs/php_errors.log'); // Make sure 'logs' directory exists and is writable
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); // Log all errors except notices, strict, and deprecated
}


/**
 * Session Configuration (Optional, but good practice)
 *
 * Customizes session behavior. Must be called before session_start().
 */
// ini_set('session.gc_maxlifetime', 1440); // Session lasts for 24 minutes (1440 seconds)
// session_set_cookie_params(1440); // Cookie also lasts for 24 minutes
// session_name('MONEYMATESESSID'); // Custom session name for security

// Add any other global configurations as needed
// define('SOME_API_KEY', 'your_secret_api_key_here');

?>