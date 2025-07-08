<?php
// Database connection parameters
$host = 'localhost'; // Your database host (often 'localhost')
$dbname = 'your_database_name'; // **CHANGE THIS to your actual database name**
$username = 'your_db_username'; // **CHANGE THIS to your actual database username**
$password = 'your_db_password'; // **CHANGE THIS to your actual database password**

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    // Create a new PDO instance
    $dbh = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    // This makes PDO throw exceptions on errors, which is good for debugging and error handling
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set default fetch mode to associative array
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // You can uncomment the line below for debugging purposes to confirm connection
    // echo "Database connected successfully!";

} catch (PDOException $e) {
    // If connection fails, display an error message and exit
    // In a production environment, you might log the error instead of displaying it directly
    die("Database connection failed: " . $e->getMessage());
}
?>