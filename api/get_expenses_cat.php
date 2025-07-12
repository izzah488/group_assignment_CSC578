<?php
// File: public/api/get_spending_data.php

session_start();
header('Content-Type: application/json'); // Tell the browser we're sending JSON

// Check if the user is logged in (crucial for fetching user-specific data)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Include the Database connection class
// Adjust path as needed based on your project's root and includes folder
require_once __DIR__ . '/../../includes/Database.php'; 

try {
    $conn = Database::getInstance()->getConnection();
    $userId = $_SESSION['id']; // Get the logged-in user's ID

    // SQL to get spending by category for the logged-in user
    // We filter for amounts < 0 to get expenses
    $sql = "SELECT category, SUM(amount) as total_spent 
            FROM transactions 
            WHERE user_id = :userId AND amount < 0 
            GROUP BY category";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format data for D3.js: ensure positive values for pie chart slices
    $formatted_data = [];
    foreach ($data as $row) {
        $formatted_data[] = [
            'category' => $row['category'],
            'value' => abs($row['total_spent']) // Use absolute value for pie chart
        ];
    }

    echo json_encode($formatted_data);

} catch (PDOException $e) {
    // Log any database errors for debugging
    error_log("API Error fetching spending data: " . $e->getMessage(), 3, LOG_FILE_PATH);
    echo json_encode(['error' => 'Failed to retrieve data.']);
}
?>
