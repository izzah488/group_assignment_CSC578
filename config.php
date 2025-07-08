<?php
/**
 * Global configuration constants
 * ── PDO-ready ──
 */

/* ---- Database ---- */
const DB_DRIVER = 'mysql';           // PDO driver (mysql, pgsql, sqlite, …)
const DB_HOST   = 'localhost';       // Hostname
const DB_NAME   = 'money_mate_db';   // Database name
const DB_USER   = 'root';            // Username
const DB_PASS   = 'danny3';                // Password

// Complete DSN string for PDO:
const DB_DSN = DB_DRIVER
            . ':host=' . DB_HOST
            . ';dbname=' . DB_NAME
            . ';charset=utf8mb4';

/* ---- Logging ---- */
const LOG_FILE_PATH = __DIR__ . '/../logs/app_errors.log'; // make sure the dir exists
?>
