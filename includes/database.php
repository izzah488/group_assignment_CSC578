<?php
// File: /path/to/your/app/includes/Database.php

// Ensure the configuration file is loaded.
// Adjust the path based on where you placed config.php relative to Database.php
require_once __DIR__ . '/../config/config.php';

class Database {
    private static $instance = null; // Holds the single instance of the class (Singleton pattern)
    private $pdo; // Holds the PDO connection object

    /**
     * Private constructor to prevent direct instantiation (Singleton pattern).
     */
    private function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            // Throw exceptions on errors, allowing for structured error handling with try/catch
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Fetch rows as associative arrays by default for easier data access
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Disable emulation for true prepared statements (CRUCIAL for SQL injection prevention)
            PDO::ATTR_EMULATE_PREPARES   => false,
            // Ensure UTF-8mb4 character set for full Unicode support (e.g., emojis)
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            // Log the detailed error message securely to a file (not to the user)
            error_log("Database Connection Error: " . $e->getMessage(), 3, LOG_FILE_PATH);

            // Display a generic, user-friendly error message to the client
            die("Sorry, we are currently experiencing technical difficulties. Please try again later.");
        }
    }

    /**
     * Get the single instance of the Database class (Singleton pattern).
     * Creates an instance if it doesn't exist, otherwise returns the existing one.
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection object.
     * @return PDO
     */
    public function getConnection() {
        return $this->pdo;
    }
}
?>