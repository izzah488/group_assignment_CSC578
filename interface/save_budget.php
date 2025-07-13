<?php
// save_budget.php
// This script handles saving or updating a user's monthly budget in the database.

session_start(); // Start the session to access user ID

// Ensure the database connection and configuration are loaded.
// Adjust paths as necessary based on your file structure.
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../dbconnection.php';

header('Content-Type: application/json'); // Set header for JSON response

$response = ['success' => false, 'message' => ''];

// 1. Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

$userID = $_SESSION['userID']; // Get the logged-in user's ID

// 2. Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data (for fetch API with 'application/json' content type)
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $amount = $data['amount'] ?? null;
    $date = $data['date'] ?? null;

    // 3. Validate input data
    if (!is_numeric($amount) || $amount <= 0 || empty($date)) {
        $response['message'] = 'Invalid budget amount or date provided.';
        echo json_encode($response);
        exit();
    }

    // Convert amount to a float
    $amount = (float)$amount;

    // 4. Process the date to get the first day of the month
    // This ensures the UNIQUE constraint (userID, budgetDate) works for a monthly budget.
    try {
        $dateTime = new DateTime($date);
        $budgetMonthStart = $dateTime->format('Y-m-01'); // Format as YYYY-MM-01
    } catch (Exception $e) {
        $response['message'] = 'Invalid date format.';
        error_log("Budget Save Error: Invalid date format - " . $e->getMessage(), 3, LOG_FILE_PATH);
        echo json_encode($response);
        exit();
    }

    try {
        // 5. Prepare SQL statement using INSERT ... ON DUPLICATE KEY UPDATE
        // This efficiently handles both inserting a new budget or updating an existing one
        // for the given user and month (due to the UNIQUE (userID, budgetDate) constraint).
        $sql = "INSERT INTO budget (userID, budgetDate, totBudget)
                VALUES (:userID, :budgetDate, :totBudget)
                ON DUPLICATE KEY UPDATE totBudget = :totBudget";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':budgetDate', $budgetMonthStart, PDO::PARAM_STR);
        $stmt->bindParam(':totBudget', $amount, PDO::PARAM_STR); // Use STR for DECIMAL

        $stmt->execute();

        $response['success'] = true;
        $response['message'] = 'Budget saved successfully!';
    } catch (PDOException $e) {
        // Log the detailed error for debugging
        error_log("Budget Save DB Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
        $response['message'] = 'Database error: Could not save budget. Please try again later.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit();
?>
