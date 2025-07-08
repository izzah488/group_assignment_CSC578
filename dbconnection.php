<?php
/** ---------------------------------------------------------
 * File: includes/dbconnection.php
 * Creates a single PDO connection in $conn.
 * -------------------------------------------------------- */

require_once __DIR__ . '/config.php';     // adjust path if needed

try {
    // Build DSN
    $dsn = 'mysql:host=' . DB_HOST .
           ';dbname=' . DB_NAME .
           ';charset=utf8mb4';

    // Recommended PDO options
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // native prepares
    ];

    // Create the connection
    $conn = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // Log detailed error
    error_log(
        'Database connection error: ' . $e->getMessage(),
        3,
        LOG_FILE_PATH
    );
    // Show generic message to user
    exit('Database connection failed. Please try again later.');
}
