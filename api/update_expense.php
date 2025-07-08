<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';
require_once '../dbconnection.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['userID'])) {
    $userID = 1; // TEMPORARY
    // $response['message'] = 'User not logged in.';
    // echo json_encode($response); $dbh = null; exit();
} else {
    $userID = $_SESSION['userID'];
}

$data = json_decode(file_get_contents('php://input'), true);

$expenseID = $data['expenseID'] ?? null;
$expTitle = $data['expTitle'] ?? null;
$expAmount = $data['expAmount'] ?? null;
$catLookupID = $data['catLookupID'] ?? null;
$expDate = $data['expDate'] ?? null;

if (empty($expenseID) || !is_numeric($expenseID) || empty($expTitle) || !is_numeric($expAmount) || $expAmount <= 0 || empty($catLookupID) || !is_numeric($catLookupID) || empty($expDate)) {
    $response['message'] = 'Invalid input data.';
    echo json_encode($response);
    $dbh = null; exit();
}

try {
    $stmt = $dbh->prepare("
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
        $response['success'] = true;
        $response['message'] = 'Expense updated successfully.';
    } else {
        $response['message'] = 'Failed to update expense.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log("API Error: update_expense.php - " . $e->getMessage());
}

$dbh = null;
echo json_encode($response);
?>