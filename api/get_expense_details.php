<?php
// File: public/api/get_detailed_expenses.php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

require_once __DIR__ . '/../../includes/config.php'; // Include config for LOG_FILE_PATH
require_once __DIR__ . '/../../includes/dbconnection.php'; // Provides $dbh

global $dbh; // Access the global database handle

try {
    // Fetch all expense transactions (amount < 0) for the logged-in user
    // Ordered by date descending for "Recent Expenses" feel
    // We'll also fetch expenseID for edit/delete operations
    $sql = "SELECT expenseID AS id, expTitle AS title, expAmount AS amount, ecl.catName AS category, expDate AS transaction_date
            FROM expenses exp
            JOIN expCatLookup ecl ON exp.catLookupID = ecl.catLookupID
            WHERE exp.userID = :userID
            ORDER BY exp.expDate DESC, exp.expenseID DESC"; // Ensure you're filtering by user and linking to category name

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':userID', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format data: Convert amount to positive for display as expenses
    $formatted_expenses = [];
    foreach ($expenses as $expense) {
        $expense['amount'] = abs($expense['amount']); // Convert to positive for display
        $formatted_expenses[] = $expense;
    }

    echo json_encode(['success' => true, 'data' => $formatted_expenses]);

} catch (PDOException $e) {
    error_log("API Error fetching detailed expenses: " . $e->getMessage(), 3, LOG_FILE_PATH);
    echo json_encode(['success' => false, 'error' => 'Failed to retrieve expenses data.']);
}
?>