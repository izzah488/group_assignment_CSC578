<?php
session_start();

// Check CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Token is missing or invalid - reject the request
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'CSRF token validation failed.']);
    exit;
}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';
require_once '../dbconnection.php';

global $conn; // Access the global database handle

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['userID'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response); $conn = null; exit();
} else {
    $userID = $_SESSION['userID'];
}

$data = $_POST; // Using $_POST directly as the form sends FormData

$expenseID = $data['expenseID'] ?? null;
$expTitle = $data['expTitle'] ?? null;
$expAmount = $data['expAmount'] ?? null; // This should be negative from JS
$catLookupID = $data['catLookupID'] ?? null;
$expDate = $data['expDate'] ?? null;

if (empty($expenseID) || !is_numeric($expenseID) || empty($expTitle) || !is_numeric($expAmount) || $expAmount >= 0 || empty($catLookupID) || !is_numeric($catLookupID) || empty($expDate)) {
    $response['message'] = 'Invalid input data.';
    echo json_encode($response);
    $conn = null; exit();
}

try {
    $stmt = $conn->prepare("
        UPDATE expenses 
        SET 
            expTitle = :expTitle, 
            expAmount = :expAmount, 
            catLookupID = :catLookupID, 
            expDate = :expDate
        WHERE expenseID = :expenseID AND userID = :userID
    ");

    $stmt->bindParam(':expTitle', $expTitle, PDO::PARAM_STR);
    $stmt->bindParam(':expAmount', $expAmount, PDO::PARAM_STR); // Use STR for DECIMAL
    $stmt->bindParam(':catLookupID', $catLookupID, PDO::PARAM_INT);
    $stmt->bindParam(':expDate', $expDate, PDO::PARAM_STR);
    $stmt->bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Expense updated successfully.';
        } else {
            $response['message'] = 'Expense not found or not authorized to update.';
        }
    } else {
        $response['message'] = 'Failed to update expense.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log("Update Expense DB Error: " . $e->getMessage(), 3, LOG_FILE_PATH);
}

echo json_encode($response);
$conn = null; exit();
?>