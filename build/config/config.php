<?php
// File: /path/to/your/app/config/config.php

// Database connection parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // <--- CHANGE THIS FOR PRODUCTION!
define('DB_PASS', ''); // <--- CHANGE THIS FOR PRODUCTION!
define('DB_NAME', '');

// Path for error logging
// This directory (e.g., /path/to/your/app/logs/) must exist and be writable by your web server user.
define('LOG_FILE_PATH', __DIR__ . '/../logs/app_errors.log');
// __DIR__ is the directory where 'config.php' resides.
// '../logs/app_errors.log' means go up one directory from config/ then into logs/
?>```