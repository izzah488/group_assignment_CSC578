<?php
// Database connection parameters
$host = 'localhost'; // Your database host (often 'localhost')
$dbname = 'money_mate_db'; // **CHANGE THIS to your actual database name**
$username = 'root'; // **CHANGE THIS to your actual database username**
$password = ''; // **CHANGE THIS to your actual database password**

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

require_once __DIR__ . '/config.php'; // Assuming config.php is in the same directory as dbconnection.php
                                     // Or adjust path: e.g., '../config.php' if dbconnection.php is in 'includes/'
                                     // and config.php is in the parent directory (project root)

try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // In development, let's temporarily show the full error for better debugging
    die("ERROR: Database connection failed: " . $e->getMessage()); // This will stop execution and show details
}

